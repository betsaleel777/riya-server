<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperProprietaire
 */
class Proprietaire extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'nom_complet', 'telephone', 'email', 'cni'];

    protected $dates = ['created_at'];

    public function genererCode(): void
    {
        $this->attributes['code'] = 'PRO' . Str::upper(Str::random(5));
    }
}
