<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
