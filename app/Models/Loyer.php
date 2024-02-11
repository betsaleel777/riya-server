<?php

namespace App\Models;

use App\Enums\PayableStatus;
use App\Enums\ValidableEntityStatus;
use App\StateMachines\LoyerStatusStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;

/**
 * @mixin IdeHelperLoyer
 */
class Loyer extends Model implements ContractsAuditable
{
    use HasStateMachines;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use Auditable;

    protected $fillable = ['code', 'contrat_id', 'montant', 'mois'];
    protected $casts = ['montant' => 'integer'];
    protected $dates = ['created_at'];
    public $stateMachines = [
        'status' => LoyerStatusStateMachine::class,
    ];

    public function genererCode(): void
    {
        $this->attributes['code'] = 'LOY' . Str::upper(Str::random(3)) . Carbon::now()->format('y');
    }

    public function setUnpaid(): void
    {
        $this->status()->transitionTo(PayableStatus::UNPAID->value);
    }

    public function setPaid(): void
    {
        $this->status()->transitionTo(PayableStatus::PAID->value);
    }

    //scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->whereHas('paiements', fn(Builder $query): Builder => $query->pending());
    }

    public function scopeCurrentMonth(Builder $query): Builder
    {
        // created_at sera changé en mois qui sera un nouveau attribut à ajouter pour pouvoir gérer les avances sur le loyer
        return $query->whereMonth('created_at', now()->format('m'));
    }

    //relations
    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contrat::class);
    }

    public function paiements(): MorphMany
    {
        return $this->morphMany(Paiement::class, 'payable');
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

    public function pendingPaiement(): MorphOne
    {
        return $this->paiements()->one()->where('status', ValidableEntityStatus::WAIT->value);
    }

    public function firstPaiement(): MorphOne
    {
        return $this->morphOne(Paiement::class, 'payable')->oldestOfMany();
    }

    public function dette(): MorphOne
    {
        return $this->morphOne(Dette::class, 'origine');
    }
}
