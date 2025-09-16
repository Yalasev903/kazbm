<?php

namespace App\Models;

use App\Traits\ImageUpload;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class Article extends Model
{
    use HasFactory, ImageUpload;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'small_image',
        'image',
        'date',
        'body',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'status',
        'is_popular',
        'lang'
    ];

    public function getPublishedAt(): string
    {
        return Carbon::make($this->date)->format('d.m.Y');
    }

    public function getShortDesc(int $limit = 147): string
    {
        if (strlen($this->description) > $limit)
            return mb_substr($this->description, 0, $limit) . '...';

        return $this->description;
    }

    public function getList(int $limit = 9): LengthAwarePaginator
    {
        return Article::query()
            ->select(['small_image', 'title', 'description', 'date', 'slug'])
            ->where('status', true)
            ->where('lang', App::getLocale())
            ->orderByDesc('date')
            ->paginate($limit);
    }

    public function getPopular(): Collection
    {
        return Article::query()
            ->select(['small_image', 'title', 'description', 'date', 'slug'])
            ->where('status', true)
            ->where('is_popular', true)
            ->orderByDesc('date')
            ->get();
    }
}
