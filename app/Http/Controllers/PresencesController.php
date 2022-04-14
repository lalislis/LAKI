<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Media;
use App\Models\Presences;

use Illuminate\Http\Request;

class PresencesController extends Controller
{
    public function store(Request $request, User $user){
        $request->validate([
            'foto' => 'required'
        ]);

        $foto = $request->file('foto');
        
        $media = Media::create([
            'user_id' => $user->id,
            'text' => ' ',
            'storage_path' => $foto->getClientOriginalName()
        ]);

        $dir = 'image/presensi';
        $foto->move($dir,$foto->getClientOriginalName());

        $presence = Presences::create([
            'user_id' => $user->id,
            'media_id' => $media->id
        ]);
        
        $presence = $presence->where('id', $presence->id)->get();

        return $presence;
        
    }
}
