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
        "h1",
        "oblic_seo_title",
        "oblic_meta_description",
        "oblic_meta_keywords",
        "oblic_h1",
        "oblic_content"
    ];

    public $translatable = [
        'name',
         'seo_title',
         'meta_description',
        'meta_keywords',
        'h1',
        'oblic_seo_title',
        'oblic_meta_description',
        'oblic_meta_keywords',
        'oblic_h1',
        'oblic_content'];

    protected $casts = [
        // существующие касты...
        'oblic_seo_title' => 'array',
        'oblic_meta_description' => 'array',
        'oblic_meta_keywords' => 'array',
        'oblic_h1' => 'array',
        'oblic_content' => 'array',
    ];

}
