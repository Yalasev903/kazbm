<?php

namespace App\Models\Filters;

class ProductFilter extends QueryFilter
{

    public function query(string $search)
    {
        $this->builder->where('title', 'LIKE', "%{$search}%");
    }

    public function price(string $prices)
    {
        list($from, $to) = explode(',', $prices);
        if ($from && $to) {
            $this->builder->whereBetween('price', [$from, $to]);
        }
    }

    public function size(string $sizes)
    {
        $this->builder->whereHas('size', function ($q) use ($sizes) {
            $q->whereIn('id', explode(',', $sizes));
        });
    }

    public function color(string $colors)
    {
        $this->builder->whereHas('color', function ($q) use ($colors) {
            $q->whereIn('id', explode(',', $colors));
        });
    }
}
