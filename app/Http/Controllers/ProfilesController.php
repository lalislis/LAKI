<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profiles;
use App\Models\Media;
use App\Models\User;

class ProfilesController extends Controller
{
    public function index(){
        $profiles = Profiles::latest()->get();
        return $profiles;
    }

    public function show(Profiles $profile){
        return $profile;
    }

    public function update(Request $request, Profiles $profile){
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);

        
        $profile->update([
            'name' => $request->name
        ]);

        $user = User::where('id', $profile->user_id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        return $profile;
        return $user;
    }
}
