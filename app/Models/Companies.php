<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Companies extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $with = ['media'];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profiles::class);
    }
}
