<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInvoice extends Model
{
    use HasFactory;

    protected $casts = [
        'data' => 'array'
    ];

    protected $fillable = [
        'id_hash',
        'user_id',
        'order_id',
        'data'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
