<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

trait HasCountDateFilterScope
{
    public function scopeCountDateFilter(Builder $query, array | string $date): Builder
    {
        return $query->when(Arr::accessible($date), fn(Builder $query): Builder =>
            $query->whereBetween('created_at', [$date[0], $date[1]]))
            ->when(!Arr::accessible($date), fn(Builder $query): Builder => $query->where('created_at', $date));
    }
}
