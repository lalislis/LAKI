<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        return response()->json([
            'success' => true,
            'messages' => 'Success Login',
            'data' => [
                'id'   => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'remember_token' => $user->remember_token,
                'status' => $user->status
            ]
        ]);
    }

    public function register(User $user)
    {
        $req = $request->all();
        $validator = Validator::make($req, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }

        $user->create($request->all());

        return response()->json([
            'success' => true,
            'messages' => 'Success Register',
            'data' => $req
        ]);
    }

    public function logout(){
        Auth::logout();
        return response()->json([
            'success' => true,
            'messages' => 'Success Logout',
        ]);
    }
}
