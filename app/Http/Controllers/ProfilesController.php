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

    public function show(User $user){
        $profile = Profiles::where('user_id', $user->id)->get();
        return $profile;
    }

    public function update(Request $request, User $user){
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);

        $user->update([
            'email' => $request->email
        ]);

        $updateProfile = Profiles::where('user_id', $user->id)->update([
            'name' => $request->name
        ]);
        
        $profile = Profiles::where('user_id', $user->id)->get();

        return $profile;
    }
}
