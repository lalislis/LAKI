<?php

namespace App\Http\Controllers;

use App\Models\{User, Profiles};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

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
        $user->update();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'messages' => 'Success Login',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function register(User $user, Request $request)
    {
        $req = $request->all();
        $validator = Validator::make($req, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required'],
            'name' => ['required', 'string'],
            'position' => ['required', 'string'],
            'media_id' => ['required', 'integer'],
            'company_id' => ['required', 'integer'],
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

        $getUser = User::where('email', $request->email)->first();

        Profiles::create([
            'user_id' => $getUser->id,
            'media_id' => $request->media_id,
            'name' => $request->name,
            'position' => $request->position,
            'company_id' => $request->company_id
        ]);
        $data = Arr::except($req, ['password']);

        return response()->json([
            'success' => true,
            'messages' => 'Success Register',
            'data' => $data,
        ]);
    }

    public function logout(){
        $user = auth()->user();
        $user->tokens()->delete();
        $user->status = false;
        $user->update();
        return response()->json([
            'success' => true,
            'messages' => 'Success Logout',
        ]);
    }

    public function reset(){
        $user = User::where('email', request('email'))->first();
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan',
                'data' => ''
            ], 401);
        }
        $user->update([
            'password' => bcrypt(request('password'))
        ]);
        return response()->json([
            'success' => true,
            'messages' => 'Success Reset Password',
            'data' => ''
        ]);
    }

    public function forgot(){

    }
}
