<?php

namespace App\Models;

use App\Enums\AvanceStatus;
use App\Enums\ValidableEntityStatus;
use App\StateMachines\ValidableEntityStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperVisite
 */
class Visite extends Model
{
    use HasFactory, HasStateMachines;
    protected $fillable = ['code', 'personne_id', 'montant', 'date_expiration', 'appartement_id', 'frais_dossier'];

    protected $dates = ['created_at'];
    protected $casts = ['montant' => 'integer', 'date_expiration' => 'date', 'frais_dossier' => 'integer'];

    public $stateMachines = [
        'status' => ValidableEntityStateMachine::class,
    ];

    public function statusAvance(): string
    {
        $this->relationLoaded('contrat') ?: $this->load('contrat');
        if ($this->exists() and empty($this->contrat)) {
            return AvanceStatus::CONTRACTWITHOUT->value;
        }
        if ($this->exists() and !empty($this->contrat)) {
            $this->relationLoaded('avance') ?: $this->load('avance');
            return Carbon::now()->isBefore($this->contrat->debut->addMonth($this->avance->mois)) ?
            AvanceStatus::INUSE->value : AvanceStatus::EXHAUSTED->value;
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
}
