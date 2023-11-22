<?php
namespace App\Traits;

use App\Enums\BienStatus;
use Illuminate\Database\Eloquent\Builder;

trait HasProperty
{
    public function setFree(): void
    {
        $this->status()->transitionTo(BienStatus::FREE->value);
    }

    public function setBusy(): void
    {
        $this->status()->transitionTo(BienStatus::BUSY->value);
    }

    public function isBusy(): bool
    {
        return $this->status === BienStatus::BUSY->value;
    }

    public function isFree(): bool
    {
        return $this->status === BienStatus::FREE->value;
    }

    public function scopeBusy(Builder $query): Builder
    {
        return $query->where('status', BienStatus::BUSY->value);
    }

    public function scopeFree(Builder $query): Builder
    {
        return $query->where('status', BienStatus::FREE->value);
    }

}
