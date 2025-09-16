<?php

namespace App\Traits;

use App\Models\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Filterable
{
    /**
     * @param Builder $builder
     * @param QueryFilter $filter
     */
    public function scopeFilter(Builder $builder, Request $request)
    {
        $filterModel = app($this->filterClass, compact('request'));
        $filterModel->apply($builder);
    }

}
