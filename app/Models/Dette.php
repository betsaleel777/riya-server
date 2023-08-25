<?php

namespace App\Models;

use App\Enums\PayableStatus;
use App\StateMachines\DetteStatusStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Dette extends Model
{
    use HasStateMachines;

    protected $fillable = ['montant'];
    protected $casts = ['montant' => 'integer'];
    protected $dates = ['created_at'];
    public $stateMachines = [
        'status' => DetteStatusStateMachine::class
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

    public function setPending(): void
    {
        $this->status()->transitionTo(PayableStatus::PENDING->value);
    }

    public function setPaid(): void
    {
        $this->status()->transitionTo(PayableStatus::PAID->value);
    }

    public function origine(): MorphTo
    {
        return $this->morphTo();
    }
}