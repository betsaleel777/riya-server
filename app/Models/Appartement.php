<?php

namespace App\Models;

use App\Enums\BienStatus;
use App\StateMachines\AppartementStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperAppartement
 */
class Appartement extends Model
{
    use HasFactory, HasStateMachines;
    protected $fillable = [
        'reference', 'nom', 'ville', 'pays', 'quartier', 'observation', 'attestation_villageoise',
        'titre_foncier', 'document_cession', 'arreter_approbation', 'superficie', 'montant_location',
        'montant_investit', 'cout_achat', 'proprietaire_id', 'cours_commune', 'placard', 'etage',
        'toilette', 'cuisine', 'garage', 'parking', 'cie', 'sodeci', 'cloture', 'type_appartement_id', 'observation'
    ];

    public $stateMachines = [
        'status' => AppartementStateMachine::class
    ];

    protected $dates = ['created_at'];

    public function genererCode(): void
    {
        $this->attributes['reference'] = 'APP' . Str::upper(Str::random(5));
    }

    public function setFree()
    {
        $this->status()->transitionTo($to = BienStatus::FREE->value);
    }

    public function setBusy()
    {
        $this->status()->transitionTo($to = BienStatus::BUSY->value);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeAppartement::class, 'type_appartement_id');
    }

    public function proprietaire(): BelongsTo
    {
        return $this->belongsTo(Proprietaire::class);
    }

    public function visite(): MorphOne
    {
        return $this->morphOne(Visite::class, 'bien');
    }
}
