<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitySectionContent extends Model
{
    use HasFactory;

    protected $fillable = ['city_id', 'section', 'content'];

    protected $casts = [
        'content' => 'array',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public static function getContent(int $cityId, string $section): ?array
    {
        $record = static::where('city_id', $cityId)
            ->where('section', $section)
            ->first();

        return $record?->content;
    }

    public static function setContent(int $cityId, string $section, array $content): void
    {
        static::updateOrCreate(
            ['city_id' => $cityId, 'section' => $section],
            ['content' => $content]
        );
    }
}
