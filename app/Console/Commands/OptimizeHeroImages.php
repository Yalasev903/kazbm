<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;

class OptimizeHeroImages extends Command
{
    protected $signature = 'images:optimize-hero';
    protected $description = 'Optimize hero images for LCP';

    public function handle()
    {
        $heroImages = [
            storage_path('app/public/settings/01K8B3RYC80NB22Y519SND6A98.webp') => [669, 341], // Реальный размер контейнера
        ];

        foreach ($heroImages as $path => $dimensions) {
            if (file_exists($path)) {
                $this->optimizeForLCP($path, $dimensions[0], $dimensions[1]);
            }
        }
    }

    private function optimizeForLCP($path, $containerWidth, $containerHeight)
    {
        try {
            $image = Image::make($path);

            // Ресайз точно под размер контейнера
            if ($image->width() > $containerWidth || $image->height() > $containerHeight) {
                $image->resize($containerWidth, $containerHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // Сильное сжатие для hero image
                $image->save($path, 50); // 50% качество
                $this->info("✓ Hero image optimized: " . basename($path));
            }

        } catch (\Exception $e) {
            $this->error("Hero optimization failed: " . $e->getMessage());
        }
    }
}
