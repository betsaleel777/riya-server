<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTypeTerrain
 */
class TypeTerrain extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];

    protected $dates = ['created_at'];
}
