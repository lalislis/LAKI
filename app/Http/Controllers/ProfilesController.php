<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Profiles;
use App\Models\Media;
use App\Models\User;
use App\Models\Task;
use App\Models\Presences;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfilesController extends Controller
{
    public function index(){
        $profiles = Profiles::latest()->get();
        return $profiles;
    }

    public function show(){
        $profile = Profiles::whereBelongsTo(Auth::user())->first();        
        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => $profile,
        ]);
    }

    public function editPassword(Request $request){
        $req = $request->all();

        $user = Auth::user();
        $validator = Validator::make($req, [
            'password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!\Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
                }],
            'new_password' => ['required', 'string'],
            'confirm_new_password' => ['required', 'string', 'same:new_password']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return response()->json([
            'success' => true,
            'messages' => 'Your password have been changed succesfully',
        ]);
    }

    public function update(Request $request){
        $req = $request->all();

        $validator = Validator::make($req, [
            'name' => ['required', 'string'],
            'email' => ['required', 'string']
        ]);

        $user = Auth::user();
        $user->email = $request->email;
        $user->save();

        $profile = Profiles::where('user_id', Auth::user()->id)->first();
        $profile->name = $request->name;
        $profile->save();
        
        return response()->json([
            'success' => true,
            'messages' => 'Your profile have been changed succesfully',
        ]);
    }

    public function updateFoto(Request $request){
        $req = $request->all();

        $validator = Validator::make($req, [
            'foto' => ['required']
        ]);

        $user = Auth::user();
        $profile = Profiles::whereBelongsTo($user)->first();

        $foto = $request->file('foto');
        $dir = 'image/profile';
        $foto->move($dir,$foto->getClientOriginalName());

        $media = Media::where('id', $profile->media_id)->first();
        $media->storage_path = $foto->getClientOriginalName();
        $media->save();

        return response()->json([
            'success' => true,
            'messages' => 'Your photo profile have been changed succesfully',
        ]);
    }
}
