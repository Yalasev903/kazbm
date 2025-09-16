<?php

namespace App\Listeners;

use App\Events\PaymentInvoiceEvent;
use App\Models\Entities\MailEntity;
use App\Services\MailService;

class SendPaymentInvoiceListener
{

    private $mailService;

    /**
     * Create the event listener.
     */
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentInvoiceEvent $event): void
    {
        $mailEntity = $this->getMailEntity($event);
        $this->mailService->send($mailEntity);
    }

    protected function getMailEntity($event): MailEntity
    {

        $order = $event->orderInvoice->order;
        $invoiceUrl = route('order.invoice.show', $event->orderInvoice->id_hash);

        $mailEntity = new MailEntity();
        $mailEntity->sendTo = '017@i-marketing.kz, '. $order->email;
        $mailEntity->subject = 'kazbm.kz: Отправка счет на оплату';
        $mailEntity->message = view('emails.new_order', compact('invoiceUrl'));

        return $mailEntity;
    }
}
