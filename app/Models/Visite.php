<?php

namespace App\Models;

use App\Enums\AvanceStatus;
use App\Enums\ValidableEntityStatus;
use App\StateMachines\ValidableEntityStateMachine;
use App\Traits\HasCurrentYearScope;
use App\Traits\HasResponsible;
use App\Traits\HasValidableEntityScope;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

/**
 * @mixin IdeHelperVisite
 */
class Visite extends Model implements ContractsAuditable
{
    use Auditable, HasResponsible, HasStateMachines, HasCurrentYearScope, HasValidableEntityScope;
    protected $fillable = ['code', 'personne_id', 'montant', 'date_expiration', 'appartement_id', 'frais_dossier'];
    protected $dates = ['created_at'];
    protected $casts = ['montant' => 'integer', 'date_expiration' => 'date', 'frais_dossier' => 'integer'];

    public $stateMachines = [
        'status' => ValidableEntityStateMachine::class,
    ];

    public function statusAvance(): string
    {
        $this->loadMissing('contrat');
        if ($this->exists() and empty($this->contrat)) {
            return AvanceStatus::CONTRACTWITHOUT->value;
        }
        if ($this->exists() and !empty($this->contrat)) {
            $this->loadMissing('avance');
            return Carbon::now()->isBefore($this->contrat->debut->addMonth($this->avance->mois)) ?
            AvanceStatus::INUSE->value : AvanceStatus::EXHAUSTED->value;
        }
    }

    public function bailProcessStarted(): bool
    {
        if ($this->exists) {
            $this->loadMissing('avance');
            $this->loadMissing('frais');
            $this->loadMissing('caution');
            return !empty($this->avance) or !empty($this->frais) or !empty($this->caution) or $this->frais_dossier !== 0;
        }
    }

    public function getAmountTotaly(): int
    {
        if ($this->exists) {
            $this->loadMissing('avance');
            $this->loadMissing('frais');
            $this->loadMissing('caution');
            $this->loadMissing('appartement');
            return $this->attributes['frais_dossier'] + $this->attributes['montant'] +
            ($this->avance?->mois + $this->frais?->mois + $this->caution?->mois) * $this->appartement->montant_location;
        }
    }

    public function setExpiration()
    {
        $this->attributes['date_expiration'] = Carbon::now()->addMonth(3);
    }
    public function genererCode(): void
    {
        $this->attributes['code'] = 'VIS' . Str::upper(Str::random(5));
    }

    public function setValide(): void
    {
        $this->status()->transitionTo($to = ValidableEntityStatus::VALID->value);
    }

    public function checkCreateContrat(): bool
    {
        return !empty($this->attributes['caution']) and !empty($this->attributes['avance']) and !empty($this->attributes['frais']);
    }

    //relations
    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class);
    }

    public function frais(): HasOne
    {
        return $this->hasOne(Frais::class);
    }

    public function caution(): HasOne
    {
        return $this->hasOne(Caution::class);
    }

    public function avance(): hasOne
    {
        return $this->HasOne(Avance::class);
    }

    public function appartement(): BelongsTo
    {
        return $this->belongsTo(Appartement::class);
    }

    public function contrat(): MorphOne
    {
        return $this->MorphOne(Contrat::class, 'operation');
    }

    public function dette(): MorphOne
    {
        return $this->morphOne(Dette::class, 'origine');
    }
}
