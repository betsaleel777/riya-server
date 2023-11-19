<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin IdeHelperSociete
 */
class Societe extends Model implements HasMedia, ContractsAuditable
{
    use Auditable, InteractsWithMedia;

    protected $fillable = [
        'raison_sociale', 'description', 'slogan', 'email', 'boite_postale',
        'forme_juridique', 'registre', 'contact', 'siege', 'frais_dossier',
    ];
    protected $casts = ['frais_dossier' => 'integer'];
    protected $with = ['logo'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function logo(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')->where('collection_name', '=', 'logo');
    }
}
