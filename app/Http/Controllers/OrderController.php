<?php

namespace App\Http\Controllers;

use App\Events\PaymentInvoiceEvent;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\MailService;
use App\Helpers\TelegramHelper;
use App\Models\Entities\MailEntity;

class OrderController extends CartController
{
       private $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    public function invoice(Request $request)
    {
        $invoice = OrderInvoice::query()
            ->where('id_hash', $request->id_hash)
            ->orderByDesc('id')
            ->firstOrFail();

        $invoiceHtml = view('pdf.order_invoice', ['order' => $invoice->order])->render();

        return Pdf::loadHTML($invoiceHtml)
            ->stream("invoice-" . now()->format('Y-m-d_H_i') . ".pdf");
    }


    public function store(OrderRequest $request, Order $order)
    {
        $order->fill($request->validated());
        $order->products = $this->cart()->getContent();

        if ($order->save()) {

            $this->sendOrderNotification($order);
            if ($order->isPaymentInvoice()) {
                $orderInvoice = $this->createInvoice($order->id);
                event(new PaymentInvoiceEvent($orderInvoice));
            }

            $invoiceUrl = null;
            if ($order->payment == 0) {
                $invoice = $this->generateInvoicePaymentUrl($order);
                $invoiceUrl = is_array($invoice) ? $invoice['invoice_url'] ?? null : null;
            }

            // История пользователя
            if ($currentUser = $this->cart()->user()) {
                $this->createHistory($order->id);
            }

            $this->cart()->clear();

            return response()->json([
                'status' => 'success',
                'message' => 'Заказ успешно создан!',
                'invoice_url' => $invoiceUrl,
                'is_guest' => !$currentUser,
            ]);
        }

        return response()->json(['status' => 'error'], 500);
    }

    protected function createHistory(int $orderId)
    {
        $user = $this->cart()->user();
        if (!$user) return null;

        $history = new OrderHistory();
        $history->user_id = $user->id;
        $history->order_id = $orderId;
        $history->save();

        return $history;
    }

    private function generateInvoicePaymentUrl(Order $order): ?array
    {
        $clientId = 'KAZBM.KZ';
        $clientSecret = 'x9t7!V!VLe)oExF$';
        $shopId = '053bd98e-d188-45dc-98d3-2126939e7149';

        // 1. Получение токена
        $tokenResponse = Http::asForm()->post('https://epay-oauth.homebank.kz/oauth2/token', [
            'grant_type' => 'client_credentials',
            'scope' => 'payment',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        if (!$tokenResponse->ok()) {
            Log::error('Ошибка получения токена EPAY: ' . $tokenResponse->body());
            return null;
        }

        $accessToken = $tokenResponse->json('access_token');

 $payload = [
    'shop_id' => $shopId,
    'account_id' => (string) ($order->user_id ?? 'guest'),
    'invoice_id' => str_pad($order->id, 8, '0', STR_PAD_LEFT),
    'amount' => (int) ($order->getData('total')),
    'language' => 'rus',
    'description' => 'Оплата заказа №' . $order->id,
    'expire_period' => '1d',
    'recipient_contact' => $order->email ?? 'noemail@example.com',
    'recipient_contact_sms' => $order->phone ?? '+77000000000',
    'currency' => 'KZT',

    // URL для получения результата платежа (сервер)
    'post_link' => 'https://kazbm.kz/api/payment/webhook',
    'failure_post_link' => 'https://kazbm.kz/api/payment/webhook',

    // URL для возврата пользователя на сайт (браузер)
    'back_link' => 'https://kazbm.kz/profile/history',
    'failure_back_link' => 'https://kazbm.kz/profile/history',
];


        $invoiceResponse = Http::withToken($accessToken)
            ->post('https://epay-api.homebank.kz/invoice', $payload);

        if ($invoiceResponse->ok()) {
            return $invoiceResponse->json();
        }

        Log::error('Ошибка создания счета EPAY: ' . $invoiceResponse->body());
        return null;
    }

    // 🔴 ОБНОВЛЯЕМ МЕТОД ДЛЯ ОТПРАВКИ УВЕДОМЛЕНИЙ О ЗАКАЗАХ
    private function sendOrderNotification(Order $order)
    {
        try {
            $products = $order->products ?? [];
            $productList = '';
            foreach ($products as $product) {
                $name = $product['name'] ?? 'Товар';
                $qty = $product['quantity'] ?? 1;
                $price = number_format($product['price'] ?? 0, 0, '.', ' ');
                $productList .= "▪️ {$name} — {$qty} шт. × {$price} ₸\n";
            }

            $total = number_format($order->getData("total") ?? 0, 0, '.', ' ');
            $name = $order->name ?? 'Имя не указано';
            $surname = $order->surname ?? 'Фамилия не указана';

            // Форматируем телефон для кликабельной ссылки
            $phoneLink = TelegramHelper::createPhoneLink($order->phone);

            $message = "🛒 *Новый заказ!*\n\n"
                . "*Номер заказа:* `{$order->id}`\n"
                . "*Сумма:* `{$total} ₸`\n"
                . "*Адрес:* `{$order->getData("org_address")}`\n"
                . "*Имя:* `{$name}`\n"
                . "*Фамилия:* `{$surname}`\n"
                . "*Email:* `{$order->email}`\n"
                . "*Телефон:* {$phoneLink}\n\n"
                . "*Состав заказа:*\n"
                . "{$productList}";

            // Отправка в Telegram
            $token = env('TG_BOT_TOKEN');
            $channelId = '-1002352982230';
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $channelId,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => true,
            ]);

            // Отправка на почту через MailService
            $mailEntity = new MailEntity();
            $mailEntity->sendTo = 'sale@kazbm.kz';
            $mailEntity->sendFrom = 'sale@kazbm.kz';
            $mailEntity->subject = '🛒 Новый заказ на сайте';
            $mailEntity->message = $message;
            $this->mailService->send($mailEntity);

            Log::info("Уведомление о новом заказе №{$order->id} отправлено");

        } catch (\Exception $e) {
            Log::error("Ошибка отправки уведомления о заказе: " . $e->getMessage());
        }
    }

    public function paymentWebhook(Request $request)
    {
        Log::info('Webhook received:', $request->all());

        $invoiceId = $request->input('invoiceId');
        $paymentStatus = $request->input('reason');

        if (!$invoiceId || !$paymentStatus) {
            Log::warning('Webhook: Missing invoiceId or reason');
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $orderId = ltrim($invoiceId, '0');
        $order = Order::find($orderId);

        if (!$order) {
            Log::warning("Webhook: Order not found with ID {$orderId}");
            return response()->json(['error' => 'Order not found'], 404);
        }

        if (strtolower($paymentStatus) === 'success') {
            $order->status = 1;
            $order->save();

            $products = $order->products ?? [];
            $productList = '';
            foreach ($products as $product) {
                $name = $product['name'] ?? 'Товар';
                $qty = $product['quantity'] ?? 1;
                $price = number_format($product['price'] ?? 0, 0, '.', ' ');
                $productList .= "▪️ {$name} — {$qty} шт. × {$price} ₸\n";
            }

            $total = number_format($order->getData("total") ?? 0, 0, '.', ' ');
            $name = $order->name ?? 'Имя не указано';
            $surname = $order->surname ?? 'Фамилия не указана';

            // Форматируем телефон для кликабельной ссылки
            $phoneLink = TelegramHelper::createPhoneLink($order->phone);

            $message = "✅ *Оплата подтверждена!*\n\n"
                . "*Номер заказа:* `{$order->id}`\n"
                . "*Cумма:* `{$total} ₸`\n"
                . "*Адрес:* `{$order->getData("org_address")}`\n"
                . "*Имя:* `{$name}`\n"
                . "*Фамилия:* `{$surname}`\n"
                . "*Email:* `{$order->email}`\n"
                . "*Телефон:* {$phoneLink}\n\n"
                . "*Состав заказа:*\n"
                . "{$productList}";

            try {
                $token = env('TG_BOT_TOKEN');
                $channelId = '-1002352982230';
                Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $channelId,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                    'disable_web_page_preview' => true,
                ]);
            } catch (\Exception $e) {
                Log::error("Telegram send error: " . $e->getMessage());
            }

            try {
                // Отправка на email через MailService
                $mailEntity = new MailEntity();
                $mailEntity->sendTo = 'sale@kazbm.kz';
                $mailEntity->sendFrom = 'sale@kazbm.kz';
                $mailEntity->subject = '✅ Оплата подтверждена';
                $mailEntity->message = $message;
                $this->mailService->send($mailEntity);
            } catch (\Exception $e) {
                Log::error("Email send error: " . $e->getMessage());
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['status' => 'ignored']);
    }
}

    protected function createInvoice(int $orderId)
    {
        $user = $this->cart()->user();

        $invoice = new OrderInvoice();
        $invoice->id_hash = md5(uniqid() . mt_rand());
        $invoice->user_id = $user->id ?? null;
        $invoice->order_id = $orderId;
        $invoice->save();

        return $invoice;
    }
}
