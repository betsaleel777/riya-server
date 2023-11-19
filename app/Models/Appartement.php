<?php

namespace App\Models;

use App\Enums\BienStatus;
use App\StateMachines\AppartementStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

/**
 * @mixin IdeHelperAppartement
 */
class Appartement extends Model implements ContractsAuditable
{
    use HasFactory, Auditable, HasStateMachines;
    protected $fillable = [
        'reference', 'nom', 'ville', 'pays', 'quartier', 'observation', 'attestation_villageoise',
        'titre_foncier', 'document_cession', 'arreter_approbation', 'superficie', 'montant_location',
        'montant_investit', 'cout_achat', 'proprietaire_id', 'cours_commune', 'placard', 'etage',
        'toilette', 'cuisine', 'garage', 'parking', 'cie', 'sodeci', 'cloture', 'type_appartement_id', 'observation',
    ];

    protected $casts = [
        'attestation_villageoise' => 'boolean',
        'titre_foncier' => 'boolean',
        'document_cession' => 'boolean',
        'arreter_approbation' => 'boolean',
        'cours_commune' => 'boolean',
        'etage' => 'boolean',
        'placard' => 'boolean',
        'toilette' => 'boolean',
        'cuisine' => 'boolean',
        'garage' => 'boolean',
        'parking' => 'boolean',
        'cie' => 'boolean',
        'sodeci' => 'boolean',
        'cloture' => 'boolean',
        'superficie' => 'integer',
        'montant_location' => 'integer',
        'montant_investit' => 'integer',
        'cout_achat' => 'integer',
    ];

    public $stateMachines = [
        'status' => AppartementStateMachine::class,
    ];

    protected $dates = ['created_at'];

    public function genererCode(): void
    {
        $this->attributes['reference'] = 'APP' . Str::upper(Str::random(5));
    }

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

    //scopes
    public function scopeBusy(Builder $query): Builder
    {
        return $query->where('status', BienStatus::BUSY->value);
    }

    public function scopeFree(Builder $query): Builder
    {
        return $query->where('status', BienStatus::FREE->value);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeAppartement::class, 'type_appartement_id');
    }

    public function proprietaire(): BelongsTo
    {
        return $this->belongsTo(Proprietaire::class);
    }

    public function achat(): MorphOne
    {
        return $this->morphOne(Achat::class, 'bien');
    }
}
