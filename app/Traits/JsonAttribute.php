<?php

namespace App\Traits;

trait JsonAttribute
{

    public function getData(string $key)
    {
        return $this->getJsonData('data', $key);
    }

    protected function getJsonData(string $attribute, string $key)
    {
        return $this->{$attribute}[$key] ?? null;
    }
}
