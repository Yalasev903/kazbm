<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasFactory, HasTranslations;

        protected $fillable = [
        "name",
        "slug",
        "delivery_cost",
        "region",
        "is_default",
        "seo_title",
        "meta_description",
        "meta_keywords",
        "h1"
    ];

    public $translatable = ['name', 'seo_title', 'meta_description', 'meta_keywords', 'h1'];

}
