<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasResponsible
{
    public function responsable(): MorphOne
    {
        return $this->audits()->one()->where('event', 'created')->with('user:id,name');
    }
}
