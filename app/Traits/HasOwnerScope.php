<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasOwnerScope
{

    /**
     * Obtenir les models crée par l'utilisateur courant
     */
    public function scopeOwner(Builder $query): Builder
    {
        return $query->whereHas('audits', fn($query) => $query->where('user_id', Auth::user()->id));
    }

}
