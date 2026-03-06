<?php
namespace App\Traits;

use App\Enums\ValidableEntityStatus;
use Illuminate\Database\Eloquent\Builder;

trait HasValidableEntityScope
{
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', ValidableEntityStatus::WAIT->value);
    }

    public function scopeValidated(Builder $query): Builder
    {
        return $query->where('status', ValidableEntityStatus::VALID->value);
    }
}
