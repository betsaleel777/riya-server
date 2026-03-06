<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasResponsible
{

    public function scopeWithResponsible(Builder $query): Builder
    {
        return $query->with('audit:id,user_type,user_id,audits.auditable_id,audits.auditable_type',
            'audit.user:id,name', 'audit.user.photo:id,model_id,model_type,disk,file_name');
    }

    public function scopeWithNameResponsible(Builder $query): Builder
    {
        return $query->with('audit:id,user_type,user_id,audits.auditable_id,audits.auditable_type', 'audit.user:id,name');
    }

    public function audit(): MorphOne
    {
        return $this->audits()->one()->oldestOfMany();
    }
}
