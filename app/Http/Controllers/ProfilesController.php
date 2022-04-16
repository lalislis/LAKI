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
        $profile = Profiles::whereBelongsTo($user)->get();
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

        $updateProfile = Profiles::whereBelongsTo($user)->update([
            'name' => $request->name
        ]);
        
        $profile = Profiles::whereBelongsTo($user)->get();

        return $profile;
    }

    public function updateFoto(Request $request, User $user){
        $request->validate([
            'foto' => 'required'
        ]);

        $profile = Profiles::whereBelongsTo($user)->first();

        $foto = $request->file('foto');
        $dir = 'image/profile';
        $foto->move($dir,$foto->getClientOriginalName());

        $media = Media::where('id', $profile->media_id)->first();
        $media->update([
            'storage_path' => $foto->getClientOriginalName()
        ]);

        $profile = Profiles::where('media_id', $media->id)->first();

        return $profile;
    }
}
