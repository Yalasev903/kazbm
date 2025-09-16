<?php

namespace App\Models;

use App\Traits\ImageUpload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPattern extends Model
{
    use HasFactory, ImageUpload;

    protected $casts = [
        'data' => 'array'
    ];

    protected $fillable = [
        'name',
        'photo',
        'data',
    ];
}
