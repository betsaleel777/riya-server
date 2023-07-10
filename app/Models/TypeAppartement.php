<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTypeAppartement
 */
class TypeAppartement extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];

    protected $dates = ['created_at'];
}
