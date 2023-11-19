<?php

namespace App\Models;

use App\Enums\ValidableEntityStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

/**
 * @mixin IdeHelperAchat
 */
class Achat extends Model implements ContractsAuditable
{
    use Auditable;
    protected $fillable = ['personne_id', 'uptodate', 'code'];
    protected $dates = ['created_at'];
    protected $casts = ['uptodate' => 'boolean'];

    public function genererCode(): void
    {
        $this->attributes['code'] = 'ACH' . Str::upper(Str::random(3)) . Carbon::now()->format('y');
    }

    public function reste(): int
    {
        if ($this->exists()) {
            $this->loadMissing('paiements');
            $totalPaye = $this->paiements->sum('montant');
            $this->loadMissing('bien');
            return $this->bien->cout_achat - $totalPaye;
        } else {
            return 0;
        }
    }

    // scopes

    public function scopePending(Builder $query): Builder
    {
        return $query->whereHas('paiements', fn(Builder $query): Builder => $query->pending());
    }

    //relations
    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class);
    }

    public function bien(): MorphTo
    {
        return $this->morphTo();
    }

    public function paiements(): MorphMany
    {
        return $this->morphMany(Paiement::class, 'payable');
    }

    public function contrat(): MorphOne
    {
        return $this->morphOne(Contrat::class, 'operation');
    }

    public function pendingPaiement(): MorphOne
    {
        return $this->paiements()->one()->where('status', ValidableEntityStatus::WAIT->value);
    }

    public function firstPaiement(): MorphOne
    {
        return $this->morphOne(Paiement::class, 'payable')->oldestOfMany();
    }
}
