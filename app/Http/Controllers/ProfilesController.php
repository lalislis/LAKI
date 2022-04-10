<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profiles;

class ProfilesController extends Controller
{
    public function index(){
        $profiles = Profiles::all();
        return $profiles;
    }

    public function show(Profiles $profile){
        return $profil;
    }
}
