<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityPageSeo extends Model
{
    use HasFactory;

    protected $fillable = ['city_id', 'page_slug', 'seo_title', 'meta_description', 'meta_keywords', 'h1'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
