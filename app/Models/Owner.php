<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Authenticatable
{
    use HasRoles;
    // specify guard name for this model
    protected $guard_name = 'owner';

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }
}
