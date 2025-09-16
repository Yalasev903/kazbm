<?php

namespace App\Models;

use App\Enums\MonthEnum;
use App\Enums\OrderPaymentEnum;
use App\Enums\OrderStatusEnum;
use App\Traits\JsonAttribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, JsonAttribute;

    protected $casts = [
        'data' => 'array',
        'products' => 'array',
    ];

    protected $fillable = [
        'name',
        'surname',
        'phone',
        'email',
        'delivery',
        'payment',
        'ip_account',
        'city',
        'street',
        'house',
        'status',
        'products',
        'data'
    ];

    public function orderInvoice()
    {
        return $this->hasOne(OrderInvoice::class);
    }

    public function getStatusName(): string
    {
        return OrderStatusEnum::labels()[$this->status] ?? '';
    }

    public function isPaymentInvoice(): bool
    {
        return $this->payment == OrderPaymentEnum::PAYMENT_INVOICE;
    }

    public function getCreatedAt(): string
    {
        $date = Carbon::make($this->created_at);
        return $date->day .' '. MonthEnum::getInTheGenetiveCase($date->month) .' '. $date->year;
    }

    public function getOrderNumber(): string
    {
        $number = str_pad($this->id, 9, '0', STR_PAD_LEFT);
        return substr_replace($number, "-", 5, 0);
    }

    public function getVatAmount()
    {
        return ($this->getTotalAmount() * 12)/100;
    }

    public function getTotalAmount(): int
    {
        if ($deliveryPrice = (int)$this->getData('delivery_price')) {
            return $deliveryPrice + (int)$this->getData('total');
        }

        return (int)$this->getData('total');
    }
}
