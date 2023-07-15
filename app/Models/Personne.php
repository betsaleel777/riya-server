<?php

namespace App\Models;

use App\Enums\PersonneCiviliteEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperPersonne
 */
class Personne extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'code', 'email', 'nom_complet', 'cni', 'date_naissance',
        'lieu_naissance', 'nationalite', 'telephone', 'ville', 'quartier',
        'pays', 'animal', 'fonctions', 'photo_piece', 'civilite', 'type_client_id'
    ];
    protected $with = ['piece', 'avatar'];
    protected $dates = [
        'date_naissance', 'created_at'
    ];

    protected $casts = [
        'civilite' => PersonneCiviliteEnum::class,
        'date_naissance' => 'date'
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
}
