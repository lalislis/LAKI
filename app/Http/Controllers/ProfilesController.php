<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profiles;
use App\Models\Media;

class ProfilesController extends Controller
{
    public function index(){
        $profiles = Profiles::all();
        return $profiles;
    }

    public function show(Profiles $profile){
        return $profil;
    }

    public function update(Request $request, Profiles $profile){
        $this->validate($request, [
            'company_id' => 'required',
            'media_id' => 'required',
            'name' => 'required',
            'position' => 'required'
        ]);

        $media = Media::where('user_id', $profile->user_id);
        $media->storage_path = $request->input('storage_path');
        $profile->company_id = $request-input('company_id');
        $profile->media_id = $media->id;
        $profile->name = $request-input('name');
        $profile->position = $request-input('position');
        $media->update();
        $profile->update();

        return $profil;
    }
}
