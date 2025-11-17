<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class ImageOptimizationService
{
    public function convertToWebP($sourcePath, $quality = 80, $maxWidth = 1920)
    {
        if (!file_exists($sourcePath)) {
            return null;
        }

        $webpPath = $this->getWebpPath($sourcePath);

        // Создаем директорию если не существует
        $directory = dirname($webpPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        try {
            $image = Image::make($sourcePath);

            // Ресайз если изображение слишком большое
            if ($image->width() > $maxWidth) {
                $image->resize($maxWidth, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Конвертируем в WebP
            $image->encode('webp', $quality)->save($webpPath);

            // Оптимизируем WebP
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($webpPath);

            return $webpPath;

        } catch (\Exception $e) {
            \Log::error('WebP conversion failed: ' . $e->getMessage());
            return null;
        }
    }

    public function optimizeImage($path, $quality = 85)
    {
        if (!file_exists($path)) {
            return false;
        }

        try {
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($path);
            return true;
        } catch (\Exception $e) {
            \Log::error('Image optimization failed: ' . $e->getMessage());
            return false;
        }
    }

    private function getWebpPath($originalPath)
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
    }

    public function batchConvertDirectory($directory, $quality = 80)
    {
        if (!is_dir($directory)) {
            return ['error' => 'Directory not found'];
        }

        $results = [
            'converted' => 0,
            'skipped' => 0,
            'errors' => 0,
            'files' => []
        ];

        $images = glob($directory . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);

        foreach ($images as $image) {
            $webpPath = $this->getWebpPath($image);

            // Пропускаем если WebP уже существует и новее
            if (file_exists($webpPath) && filemtime($webpPath) >= filemtime($image)) {
                $results['skipped']++;
                $results['files'][] = [
                    'original' => $image,
                    'webp' => $webpPath,
                    'status' => 'skipped'
                ];
                continue;
            }

            $converted = $this->convertToWebP($image, $quality);

            if ($converted) {
                $results['converted']++;
                $results['files'][] = [
                    'original' => $image,
                    'webp' => $converted,
                    'status' => 'converted',
                    'original_size' => filesize($image),
                    'webp_size' => filesize($converted),
                    'savings' => filesize($image) - filesize($converted)
                ];
            } else {
                $results['errors']++;
                $results['files'][] = [
                    'original' => $image,
                    'status' => 'error'
                ];
            }
        }

        return $results;
    }
}
