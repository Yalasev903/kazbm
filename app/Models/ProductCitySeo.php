<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCitySeo extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'city_id',
        'seo_title',
        'meta_description',
        'h1',
    ];

    /**
     * Связь с городом
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Связь с товаром
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
