<?php

namespace App\Models;

use App\Enums\ValidableEntityStatus;
use App\StateMachines\ValidableEntityStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperPaiement
 */
class Paiement extends Model
{
    use HasFactory, HasStateMachines;
    protected $fillable = ['montant', 'code'];
    protected $dates = ['created_at'];
    protected $casts = ['montant' => 'integer'];

    public $stateMachines = [
        'status' => ValidableEntityStateMachine::class,
    ];

    public function genererCode(string $prefix): void
    {
        $this->attributes['code'] = $prefix . Str::upper(Str::random(3)) . Carbon::now()->format('y');
    }

    public function setValide(): void
    {
        $this->status()->transitionTo($to = ValidableEntityStatus::VALID->value);
    }

    //scope
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', ValidableEntityStatus::WAIT->value);
    }

    public function scopeValidated(Builder $query): Builder
    {
        return $query->where('status', ValidableEntityStatus::VALID->value);
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function dette(): MorphOne
    {
        return $this->morphOne(Dette::class, 'origine');
    }
}
