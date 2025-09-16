<?php

namespace App\Events;

use App\Models\OrderInvoice;
use Illuminate\Foundation\Events\Dispatchable;

class PaymentInvoiceEvent
{
    use Dispatchable;

    public $orderInvoice;

    /**
     * Create a new event instance.
     */
    public function __construct(OrderInvoice $orderInvoice)
    {
        $this->orderInvoice = $orderInvoice;
    }
}
