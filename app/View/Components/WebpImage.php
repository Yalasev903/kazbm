<?php

namespace App\View\Components;

use Illuminate\View\Component;

class WebpImage extends Component
{
    public $src;
    public $alt;
    public $class;
    public $lazy;
    public $width;
    public $height;
    public $fetchpriority;

    public function __construct($src, $alt = '', $class = '', $lazy = true, $width = null, $height = null, $fetchpriority = 'auto')
    {
        $this->src = $src;
        $this->alt = $alt;
        $this->class = $class;
        $this->lazy = $lazy;
        $this->width = $width;
        $this->height = $height;
        $this->fetchpriority = $fetchpriority;
    }

    public function getAssetPath($path)
    {
        if (str_starts_with($path, '/storage/')) {
            return asset($path);
        }
        return asset($path);
    }

    public function hasWebp()
    {
        $webpPath = getWebpPath($this->src);
        return $webpPath !== $this->src;
    }

    public function webpSrc()
    {
        return $this->getAssetPath(getWebpPath($this->src));
    }

    public function originalSrc()
    {
        return $this->getAssetPath($this->src);
    }

    public function render()
    {
        return view('components.webp-image');
    }
}
