<?php

namespace App\Models;

use App\Enums\PayableStatus;
use App\StateMachines\DetteStatusStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperDette
 */
class Dette extends Model
{
    use HasStateMachines;

    protected $fillable = ['montant'];
    protected $casts = ['montant' => 'integer'];
    protected $dates = ['created_at'];
    public $stateMachines = [
        'status' => DetteStatusStateMachine::class,
    ];

    public function genererCode(): void
    {
        $this->attributes['code'] = 'DET' . Str::upper(Str::random(3)) . Carbon::now()->format('y');
    }

    public function getOrigine(): string
    {
        if ($this->exists) {
            $this->relationLoaded('origine') ?: $this->load('origine');
            return match ($this->origine_type) {
                'App\Models\Paiement' => $this->origine->payable_type,
                default => $this->origine_type,
            };
        }
    }

    public function isVisiteResource(): bool
    {
        return str($this->getOrigine())->explode('\\')[2] === 'Visite' and $this->relationLoaded('origine')
        and $this->origine->relationLoaded('appartement') and $this->origine->appartement->relationLoaded('proprietaire');
    }

    public function isPaiementResource(): bool
    {
        return str($this->getOrigine())->explode('\\')[2] === 'Paiement' and $this->relationLoaded('origine') and $this->origine->relationLoaded('payable') and $this->origine->payable->relationLoaded('bien') and $this->origine->payable->bien->relationLoaded('proprietaire');
    }

    public function setPending(): void
    {
        $this->status()->transitionTo(PayableStatus::PENDING->value);
    }

    public function setPaid(): void
    {
        $this->status()->transitionTo(PayableStatus::PAID->value);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', PayableStatus::PENDING->value);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', PayableStatus::PAID->value);
    }

    public function origine(): MorphTo
    {
        return $this->morphTo();
    }
}
