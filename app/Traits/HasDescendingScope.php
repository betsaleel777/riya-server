<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasDescendingScope
{
    public function scopeDescending(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'DESC');
    }
}
