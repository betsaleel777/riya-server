<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasCurrentYearScope
{
    public function scopeCurrentYear(Builder $query): Builder
    {
        return $query->whereYear('created_at', now()->format('Y'));
    }
}
