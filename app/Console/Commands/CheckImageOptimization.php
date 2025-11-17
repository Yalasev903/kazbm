<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckImageOptimization extends Command
{
    protected $signature = 'images:check-optimization';
    protected $description = 'Check image optimization results';

    public function handle()
    {
        $directories = [
            storage_path('app/public/products'),
            storage_path('app/public/articles'),
            storage_path('app/public/settings'),
            public_path('images'),
        ];

        $totalStats = ['original' => 0, 'webp' => 0, 'count' => 0];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) continue;

            $images = glob($directory . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);

            foreach ($images as $image) {
                $webpPath = $this->getWebpPath($image);

                if (file_exists($webpPath)) {
                    $totalStats['original'] += filesize($image);
                    $totalStats['webp'] += filesize($webpPath);
                    $totalStats['count']++;
                }
            }
        }

        $savings = $totalStats['original'] - $totalStats['webp'];
        $savingsPercent = $totalStats['original'] > 0 ? ($savings / $totalStats['original']) * 100 : 0;

        $this->info("=== IMAGE OPTIMIZATION RESULTS ===");
        $this->info("Optimized images: {$totalStats['count']}");
        $this->info("Original size: " . $this->formatBytes($totalStats['original']));
        $this->info("WebP size: " . $this->formatBytes($totalStats['webp']));
        $this->info("Total savings: " . $this->formatBytes($savings) . " ({$savingsPercent}%)");
    }

    private function getWebpPath($path)
    {
        $pathInfo = pathinfo($path);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
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
