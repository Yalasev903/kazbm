<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageOptimizationService;
use Intervention\Image\Facades\Image;

class ConvertImagesToWebP extends Command
{
    protected $signature = 'images:convert-to-webp
                           {--dir= : Specific directory to convert}
                           {--quality=80 : WebP quality (1-100)}
                           {--force : Force reconversion}
                           {--responsive : Also generate responsive sizes}';

    protected $description = 'Convert images to WebP format';

    // Конфигурация адаптивных размеров
    private $responsiveSizes = [
        'xl' => [1200, 800],
        'lg' => [800, 600],
        'md' => [600, 400],
        'sm' => [400, 300],
        'thumb' => [300, 200]
    ];

    public function handle()
    {
        $service = new ImageOptimizationService();
        $directories = [
            storage_path('app/public/products'),
            storage_path('app/public/articles'),
            storage_path('app/public/settings'),
            public_path('images'),
        ];

        if ($this->option('dir')) {
            $directories = [$this->option('dir')];
        }

        $quality = (int)$this->option('quality');
        $totalResults = [
            'total_converted' => 0,
            'total_skipped' => 0,
            'total_errors' => 0,
            'total_savings' => 0,
            'responsive_generated' => 0
        ];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                $this->warn("Directory not found: {$directory}");
                continue;
            }

            $this->info("Processing: {$directory}");

            $results = $service->batchConvertDirectory($directory, $quality);

            $totalResults['total_converted'] += $results['converted'];
            $totalResults['total_skipped'] += $results['skipped'];
            $totalResults['total_errors'] += $results['errors'];

            foreach ($results['files'] as $file) {
                if (isset($file['savings'])) {
                    $totalResults['total_savings'] += $file['savings'];
                }

                $statusColor = $file['status'] === 'converted' ? 'green' :
                              ($file['status'] === 'skipped' ? 'yellow' : 'red');

                $this->line("<fg={$statusColor}>  {$file['status']}: " . basename($file['original']) . "</>");

                // Генерация адаптивных размеров если нужно
                if ($this->option('responsive') && $file['status'] === 'converted') {
                    $responsiveCount = $this->generateResponsiveSizes($file['original'], $quality);
                    $totalResults['responsive_generated'] += $responsiveCount;
                }
            }

            $this->newLine();
        }

        // Итоги
        $this->info('=== CONVERSION RESULTS ===');
        $this->info("Converted: {$totalResults['total_converted']}");
        $this->info("Skipped: {$totalResults['total_skipped']}");
        $this->info("Errors: {$totalResults['total_errors']}");
        $this->info("Total savings: " . $this->formatBytes($totalResults['total_savings']));

        if ($this->option('responsive')) {
            $this->info("Responsive sizes generated: {$totalResults['responsive_generated']}");
        }
    }

    /**
     * Генерация адаптивных размеров для WebP
     */
    private function generateResponsiveSizes($originalPath, $quality)
    {
        $generated = 0;
        try {
            $pathInfo = pathinfo($originalPath);
            $image = Image::make($originalPath);

            foreach ($this->responsiveSizes as $sizeName => $dimensions) {
                list($width, $height) = $dimensions;

                if ($image->width() < $width && $image->height() < $height) {
                    continue;
                }

                $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "_{$sizeName}.webp";

                $resizedImage = clone $image;
                $resizedImage->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $resizedImage->encode('webp', $quality)->save($webpPath);
                $generated++;

                $this->line("<fg=blue>    ↳ Responsive: {$sizeName} (" . $width . "x" . $height . ")</>");
            }

        } catch (\Exception $e) {
            $this->error("Responsive generation failed: " . basename($originalPath));
        }

        return $generated;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
