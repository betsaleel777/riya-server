<?php

namespace App\Traits;

use App\Models\Depense;
use Illuminate\Database\Eloquent\Builder;

trait HasCountDateFilterScope
{
    public function scopeCountDateFilter(Builder $query, string $date): Builder
    {
        $dates = explode(',', $date);
        $model = $query->getModel();
        $column = $model instanceof Depense ? 'date_depense' : 'created_at';
        return $query->when(
            count($dates) === 2,
            fn(Builder $query): Builder => $query->whereBetween($column, [$dates[0], $dates[1]])
        )->when(
            count($dates) === 1,
            fn(Builder $query): Builder => $query->whereDate($column, $dates[0])
        );
    }
}
