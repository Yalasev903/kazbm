<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;


    protected $fillable = [
        'type',
        'title',
        'sub_title',
        'description',
        'slug',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'status'
    ];
}
