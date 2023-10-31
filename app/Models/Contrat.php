<?php

namespace App\Models;

use App\Enums\ContratState;
use App\Enums\ContratStatus;
use App\StateMachines\ContratStateMachine;
use App\StateMachines\ContratStatusStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperContrat
 */
class Contrat extends Model
{
    use HasFactory, HasStateMachines;
    protected $fillable = ['debut', 'fin', 'commission'];
    protected $casts = ['commission' => 'integer', 'debut' => 'date', 'fin' => 'date'];
    protected $dates = ['created_at'];

    public $stateMachines = [
        'etat' => ContratStateMachine::class,
        'status' => ContratStatusStateMachine::class,
    ];

    public function encaissable(): bool
    {
        if ($this->exists()) {
            $this->relationLoaded('operation') and $this->operation->relationLoaded('appartement') ?: $this->load(['operation' => ['avance', 'appartement']]);
            return Carbon::now()->greaterThanOrEqualTo($this->debut->addMonth($this->operation->avance->mois));
        } else {
            return false;
        }
    }

    public function setAborted(): void
    {
        $this->etat()->transitionTo($to = ContratState::ABORTED->value);
    }

    public function setUptodate(): void
    {
        $this->status()->transitionTo($to = ContratStatus::UPTODATE->value);
    }

    public function setNotuptodate(): void
    {
        $this->status()->transitionTo($to = ContratStatus::NOTUPTODATE->value);
    }

    public function scopeProcessing(Builder $query): Builder
    {
        return $query->where('etat', ContratState::USING->value);
    }

    public function scopePurchaseProcessing(Builder $query): Builder
    {
        return $query->processing()->where('operation_type', 'App\Models\Achat');
    }

    public function scopeRentProcessing(Builder $query): Builder
    {
        return $query->processing()->where('operation_type', 'App\Models\Visite');
    }

    // sur achat et sur visite
    public function operation(): MorphTo
    {
        return $this->morphTo();
    }
}
