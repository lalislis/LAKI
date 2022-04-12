<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profiles;
use App\Models\Media;

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
            'company_id' => 'required',
            'name' => 'required',
            'position' => 'required'
        ]);

        
        $profile->update([
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
            'media_id' => $request->media_id,
            'name' => $request->name,
            'position' => $request->position
        ]);

        return $profile;
    }
}
