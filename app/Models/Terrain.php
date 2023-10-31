<?php

namespace App\Models;

use App\Enums\BienStatus;
use App\StateMachines\TerrainStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperTerrain
 */
class Terrain extends Model
{
    use HasFactory, HasStateMachines;

    protected $fillable = [
        'reference', 'nom', 'ville', 'quartier', 'pays', 'montant_investit', 'cout_achat',
        'montant_location', 'type_terrain_id', 'proprietaire_id', 'arreter_approbation',
        'document_cession', 'titre_foncier', 'attestation_villageoise', 'superficie',
    ];
    protected $casts = [
        'attestation_villageoise' => 'boolean',
        'titre_foncier' => 'boolean',
        'document_cession' => 'boolean',
        'arreter_approbation' => 'boolean',
        'montant_investit' => 'integer',
        'montant_location' => 'integer',
        'cout_achat' => 'integer',
        'superficie' => 'integer',
    ];

    public $stateMachines = [
        'status' => TerrainStateMachine::class,
    ];

    protected $dates = ['created_at'];

    public function genererCode(): void
    {
        $this->attributes['reference'] = 'TER' . Str::upper(Str::random(5));
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
        return $this->belongsTo(TypeTerrain::class, 'type_terrain_id');
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
