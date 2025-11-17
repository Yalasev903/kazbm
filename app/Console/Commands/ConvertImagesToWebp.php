<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageOptimizationService;

class ConvertImagesToWebP extends Command
{
    protected $signature = 'images:convert-to-webp
                           {--dir= : Specific directory to convert}
                           {--quality=80 : WebP quality (1-100)}
                           {--force : Force reconversion}';

    protected $description = 'Convert images to WebP format';

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
            'total_savings' => 0
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
            }

            $this->newLine();
        }

        // Итоги
        $this->info('=== CONVERSION RESULTS ===');
        $this->info("Converted: {$totalResults['total_converted']}");
        $this->info("Skipped: {$totalResults['total_skipped']}");
        $this->info("Errors: {$totalResults['total_errors']}");
        $this->info("Total savings: " . $this->formatBytes($totalResults['total_savings']));
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
