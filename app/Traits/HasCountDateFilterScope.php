<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasCountDateFilterScope
{
    public function scopeCountDateFilter(Builder $query, string $date): Builder
    {
        $dates = explode(',', $date);
        return $query->when(count($dates) === 2, fn(Builder $query): Builder =>
            $query->whereBetween('created_at', [$dates[0], $dates[1]]))->when(count($dates) === 1,
            fn(Builder $query): Builder => $query->whereDate('created_at', $dates[0]));
    }
}
