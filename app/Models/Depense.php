<?php

namespace App\Models;

use App\Enums\ValidableEntityStatus;
use App\StateMachines\ValidableEntityStateMachine;
use Asantibanez\LaravelEloquentStateMachines\Traits\HasStateMachines;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Depense extends Model
{
    use HasStateMachines;

    protected $fillable = ['titre', 'montant', 'description', 'type_depense_id'];
    protected $dates = ['created_at'];
    protected $casts = ['montant' => 'integer'];
    public $stateMachines = ['status' => ValidableEntityStateMachine::class];

    public function setValide(): void
    {
        $this->status()->transitionTo(ValidableEntityStatus::VALID->value);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeDepense::class, 'type_depense_id');
    }
}
