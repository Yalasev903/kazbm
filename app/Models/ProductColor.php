<?php

namespace App\Models;

use App\Traits\ImageUpload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory, ImageUpload;

    protected $fillable = [
        'name',
        'name_w',
        'name_m',
        'image',
    ];
}
