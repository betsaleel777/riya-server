<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasCurrentYearScope
{
    public function scopeCurrentYear(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()]);
    }
}
