<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Profiles extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profiles';
    protected $guarded = [];
    protected $with = ['user', 'company', 'media'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Companies::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
