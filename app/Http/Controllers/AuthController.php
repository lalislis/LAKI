<?php

namespace App\Http\Controllers;

use App\Models\{User, Profiles};
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
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

        if ($validator->fails()) {
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

    public function logout()
    {
        $user = auth()->user();
        $user->tokens()->delete();
        $user->status = false;
        $user->save();
        return response()->json([
            'success' => true,
            'messages' => 'Success Logout',
        ]);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'confirm_password', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'success' => true,
                'messages' => 'Password reset successfully'
            ]);
        }

        return response([
            'success' => false,
            'messages' => __($status)
        ], 422);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'messages' => 'Reset token has been sent to your email!',
            ]);
        }

        return response([
            'success' => false,
            'message' => __($status)
        ], 422);
    }
}
