<?php

namespace App\Models;

use App\Enums\LoyerStatus;
use App\StateMachines\LoyerStatusStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

class Loyer extends Model
{
    use HasStateMachines;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $fillable = ['code', 'contrat_id', 'montant'];
    protected $casts = ['montant' => 'integer'];
    protected $dates = ['created_at'];
    public $stateMachines = [
        'status' => LoyerStatusStateMachine::class
    ];

    public function genererCode(): void
    {
        $this->attributes['code'] = 'LOY' . Str::upper(Str::random(3)) . Carbon::now()->format('y');
    }

    public function setPending(): void
    {
        $this->status()->transitionTo(LoyerStatus::PENDING->value);
    }

    public function setPaid(): void
    {
        $this->status()->transitionTo(LoyerStatus::PAID->value);
    }

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contrat::class);
    }

    public function paiement(): MorphOne
    {
        return $this->morphOne(Paiement::class, 'payable');
    }

    public function client(): HasOneDeep
    {
        return $this->hasOneDeep(
            Personne::class,
            [Contrat::class, Visite::class],
            ['id', 'id', 'id'],
            ['contrat_id', ['operation_type', 'operation_id'], 'personne_id']
        );
    }

    public function bien(): HasOneDeep
    {
        return $this->hasOneDeep(
            Appartement::class,
            [Contrat::class, Visite::class],
            ['id', 'id', 'id'],
            ['contrat_id', ['operation_type', 'operation_id'], 'appartement_id']
        );
    }
}
