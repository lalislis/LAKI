<?php

namespace App\Http\Controllers;

use App\Models\{User, Profile};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }

        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)){
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah',
                'data' => ''
            ], 401);
        }
        $user = auth()->user();
        $user->status = true;
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'messages' => 'Success Login',
            'data' => $user->map->except('password'),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function register(User $user)
    {
        $req = $request->all();
        $validator = Validator::make($req, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required'],
            'name' => ['required', 'string'],
            'position' => ['required', 'string'],
            'company_id' => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }
        
        $user->create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'remember_token' => $request->remember_token,
        ]);

        Profile::create([
            'user_id' => $user->id,
            'media_id' => $request->media_id,
            'name' => $request->name,
            'position' => $request->position,
            'company_id' => $request->company_id
        ]);

        return response()->json([
            'success' => true,
            'messages' => 'Success Register',
            'data' => $req->map->except('password'),
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'messages' => 'Success Logout',
        ]);
    }
}
