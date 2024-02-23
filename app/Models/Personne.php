<?php

namespace App\Models;

use App\Enums\PersonneCiviliteEnum;
use App\Traits\HasOwnerScope;
use App\Traits\HasResponsible;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin IdeHelperPersonne
 */
class Personne extends Model implements HasMedia, ContractsAuditable
{
    use Auditable, HasResponsible, InteractsWithMedia, HasOwnerScope;
    protected $fillable = [
        'code', 'email', 'nom_complet', 'cni', 'date_naissance',
        'lieu_naissance', 'nationalite', 'telephone', 'ville', 'quartier',
        'pays', 'animal', 'fonctions', 'photo_piece', 'civilite', 'type_client_id',
    ];
    protected $dates = ['created_at'];

    protected $casts = [
        'civilite' => PersonneCiviliteEnum::class,
        'date_naissance' => 'date',
    ];

    public function genererCode(): void
    {
        $this->attributes['code'] = 'PRO' . Str::upper(Str::random(5));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('piece')->singleFile();
        $this->addMediaCollection('avatar')->singleFile();
    }

    public function piece(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')->where('collection_name', '=', 'piece');
    }

    public function avatar(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')->where('collection_name', '=', 'avatar');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeClient::class);
    }

    public function achats(): HasMany
    {
        return $this->hasMany(Achat::class);
    }

    public function visites(): HasMany
    {
        return $this->hasMany(Visite::class);
    }

    public function contratsBail(): HasManyThrough
    {
        return $this->hasManyThrough(Contrat::class, Visite::class, null, 'operation_id')->where('operation_type', Visite::class);
    }

    public function contratsAchat(): HasManyThrough
    {
        return $this->hasManyThrough(Contrat::class, Achat::class, null, 'operation_id')->where('operation_type', Achat::class);
    }
}
