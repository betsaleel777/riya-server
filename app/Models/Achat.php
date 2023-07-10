<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

/**
 * @mixin IdeHelperAchat
 */
class Achat extends Model
{
    use HasFactory, HasEagerLimit;
    protected $fillable = ['personne_id', 'uptodate', 'code'];
    protected $dates = ['created_at'];
    protected $casts = ['uptodate' => 'boolean'];

    public function genererCode(): void
    {
        $this->attributes['code'] = 'ACH' . Str::upper(Str::random(3)) . Carbon::now()->format('y');
    }

    // scopes

    public function scopeFirstPaiement(Builder $query): Builder
    {
        return $query->whereHas('paiements', function (Builder $query): Builder {
            return $query->limit(1);
        });
    }

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class);
    }

    public function bien(): MorphTo
    {
        return $this->morphTo();
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
}
