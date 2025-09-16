<?php

namespace App\Traits;

use App\Helpers\Common;

trait ImageUpload
{

    public function getWebpFormat(string $attribute)
    {
        $webpFormat = Common::getWebpByImage($this->$attribute ?? '');
        if (!$webpFormat) return '';

        return Common::getImage($webpFormat);
    }

    public function getRealFormat(string $attribute): string
    {
        return Common::getImage($this->$attribute ?? '');
    }
}
