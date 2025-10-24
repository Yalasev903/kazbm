<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationCallRequest;
use App\Http\Requests\ApplicationConsultationRequest;
use App\Models\Applications\ApplicationCall;
use App\Models\Applications\ApplicationConsultation;
use App\Services\MailService;
use App\Models\Entities\MailEntity;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    private $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    # Формы обратной связи
    public function consultation(ApplicationConsultationRequest $request, ApplicationConsultation $application)
    {
        $application->setDataAttributes($request->only(['email', 'name', 'message']));

        if ($application->save()) {
            // Отправка в Telegram
            $this->sendToTelegram("📧 Новая заявка на консультацию\nИмя: {$application->name}\nEmail: {$application->email}\nСообщение: {$application->message}");

            // Отправка на почту через MailService (старый рабочий способ)
            $mailEntity = new MailEntity();
            $mailEntity->sendTo = 'sale@kazbm.kz';
            $mailEntity->sendFrom = 'sale@kazbm.kz'; // Добавляем отправителя
            $mailEntity->subject = 'Новая заявка на консультацию';
            $mailEntity->message = "Поступила новая заявка на консультацию:\n\nИмя: {$application->name}\nEmail: {$application->email}\nСообщение: {$application->message}";
            $this->mailService->send($mailEntity);

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 500);
    }

    public function call(ApplicationCallRequest $request, ApplicationCall $application)
    {
        $application->setDataAttributes($request->only(['phone', 'name', 'message']));

        if ($application->save()) {
            // Отправка в Telegram
            $this->sendToTelegram("📞 Новая заявка на звонок\nИмя: {$application->name}\nТелефон: {$application->phone}\nСообщение: {$application->message}");

            // Отправка на почту через MailService (старый рабочий способ)
            $mailEntity = new MailEntity();
            $mailEntity->sendTo = 'sale@kazbm.kz';
            $mailEntity->sendFrom = 'sale@kazbm.kz'; // Добавляем отправителя
            $mailEntity->subject = 'Новая заявка на звонок';
            $mailEntity->message = "Поступила новая заявка на звонок:\n\nИмя: {$application->name}\nТелефон: {$application->phone}\nСообщение: {$application->message}";
            $this->mailService->send($mailEntity);

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 500);
    }

    private function sendToTelegram($message)
    {
        try {
            $token = env('TG_BOT_TOKEN');
            $channelId = '-1002352982230';

            Http::timeout(10)
                ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $channelId,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                ]);

            Log::info('Сообщение отправлено в Telegram: ' . $message);

        } catch (\Exception $e) {
            Log::error("Ошибка отправки в Telegram: " . $e->getMessage());
        }
    }
}
