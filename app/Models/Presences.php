<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Presences extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $with = ['media','user'];

    public function media(){
        return $this->belongsTo(Media::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
