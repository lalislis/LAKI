<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Media extends Model
{
    use HasFactory, SoftDeletes;

    const DEFAULT_USER = "images/default_user.jpg";
    const DEFAULT_COMPANY = "images/default_company.png";

    protected $guarded = [];

    public function company()
    {
        return $this->hasOne(Companies::class);
    }

    public function profile()
    {
        return $this->hasOne(Profiles::class);
    }

    public function presences()
    {
        return $this->hasOne(Presences::class);
    }
}
