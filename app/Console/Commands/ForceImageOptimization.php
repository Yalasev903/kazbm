<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;

class ForceImageOptimization extends Command
{
    protected $signature = 'images:force-optimize
                           {--quality=60 : Quality (1-100)}
                           {--max-width=1200 : Maximum width}
                           {--generate-sizes : Generate multiple responsive sizes}
                           {--dir= : Process specific directory only}'; // ДОБАВЛЕНО

    protected $description = 'Force image optimization with aggressive settings';

    private $responsiveSizes = [
        'xl' => [1200, 800],
        'lg' => [800, 600],
        'md' => [600, 400],
        'sm' => [400, 300],
        'thumb' => [300, 200]
    ];

    public function handle()
    {
        // ЕСЛИ УКАЗАНА КОНКРЕТНАЯ ДИРЕКТОРИЯ - ИСПОЛЬЗУЕМ ЕЕ
        if ($this->option('dir')) {
            $directories = [$this->option('dir')];
        } else {
            // ИНАЧЕ СТАНДАРТНЫЕ ДИРЕКТОРИИ
            $directories = [
                storage_path('app/public/products'),
                storage_path('app/public/articles'),
                storage_path('app/public/settings'),
                public_path('images'),
            ];
        }

        $totalSavings = 0;
        $processed = 0;

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                $this->warn("Directory not found: {$directory}");
                continue;
            }

            $images = glob($directory . '/*.{jpg,jpeg,png,JPG,JPEG,PNG,webp,WEBP}', GLOB_BRACE);

            foreach ($images as $image) {
                $result = $this->optimizeImage($image);
                if ($result['success']) {
                    $totalSavings += $result['savings'];
                    $processed++;
                    $this->info("✓ {$result['savings_kb']}KB saved: " . basename($image));
                }

                if ($this->option('generate-sizes')) {
                    $this->generateResponsiveSizes($image);
                }
            }
        }

        $this->info("\n🎉 OPTIMIZATION COMPLETE!");
        $this->info("Processed: {$processed} images");
        $this->info("Total savings: " . $this->formatBytes($totalSavings));

        if ($this->option('generate-sizes')) {
            $this->info("✅ Responsive sizes generated for all images");
        }
    }


    private function optimizeImage($path)
    {
        try {
            $originalSize = filesize($path);
            $image = Image::make($path);

            // АГРЕССИВНОЕ УМЕНЬШЕНИЕ РАЗМЕРА
            $maxWidth = $this->option('max-width');
            if ($image->width() > $maxWidth) {
                $image->resize($maxWidth, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // СИЛЬНОЕ СЖАТИЕ
            $quality = $this->option('quality');
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            if ($extension === 'webp') {
                $image->encode('webp', $quality);
            } else {
                $image->encode('jpg', $quality);
            }

            $image->save($path, $quality);
            $newSize = filesize($path);

            return [
                'success' => true,
                'savings' => $originalSize - $newSize,
                'savings_kb' => round(($originalSize - $newSize) / 1024, 1)
            ];

        } catch (\Exception $e) {
            $this->error("Optimization failed for: " . basename($path) . " - " . $e->getMessage());
            return ['success' => false];
        }
    }

    /**
     * Генерация адаптивных размеров для изображения
     */
    private function generateResponsiveSizes($originalPath)
    {
        try {
            $pathInfo = pathinfo($originalPath);
            $image = Image::make($originalPath);

            foreach ($this->responsiveSizes as $sizeName => $dimensions) {
                list($width, $height) = $dimensions;

                // Пропускаем если оригинал меньше целевого размера
                if ($image->width() < $width && $image->height() < $height) {
                    continue;
                }

                $newPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "_{$sizeName}." . $pathInfo['extension'];
                $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "_{$sizeName}.webp";

                // Создаем ресайз
                $resizedImage = clone $image;
                $resizedImage->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // Сохраняем в оригинальном формате
                $resizedImage->save($newPath, $this->option('quality'));

                // Сохраняем в WebP
                $resizedImage->encode('webp', $this->option('quality'))->save($webpPath);

                $this->info("  ↳ Generated: {$sizeName} (" . $width . "x" . $height . ")");
            }

            return true;

        } catch (\Exception $e) {
            $this->error("Responsive generation failed for: " . basename($originalPath) . " - " . $e->getMessage());
            return false;
        }
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
