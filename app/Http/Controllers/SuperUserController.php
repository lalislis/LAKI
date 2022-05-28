<?php

namespace App\Http\Controllers;

use App\Models\{User, Profiles, Media, Task, Companies};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SuperUserController extends Controller
{
    public function index(Request $request)
    {
        // $karyawan = Profiles::whereHas('user', function ($query) {
        //     $query->where('role', '1');
        // })
        //     ->where('company_id', Auth::user()->profile->company_id)
        //     ->with('user.tasks')
        //     ->with('media:id,storage_path')
        //     ->get()
        //     ->map
        //     ->only('id', 'media_id', 'name', 'position', 'user', 'media');

        $employee = Profiles::where('company_id', Auth::user()->profile->company_id)
            ->with('user.tasks', fn ($query) => $query->latest())
            ->with('user.presences', fn ($query) => $query->latest()->with('media'))
            ->latest()
            ->get();

        // if ($request->has('search')) {
        //     $search = $request->search;
        //     $karyawan = $karyawan = Profiles::whereHas('user', function ($query) {
        //         $query->where('role', '1');
        //     })
        //         ->where('company_id', Auth::user()->profile->company_id)
        //         ->where('name', 'like', '%' . $search . '%')
        //         ->with(['user.task', 'media:id,storage_path'])
        //         ->get()
        //         ->map
        //         ->only('id', 'media_id', 'name', 'position', 'user', 'media');
        //     if ($karyawan->isEmpty()) {
        //         return response()->json([
        //             'success' => false,
        //             'messages' => 'Data Cannot be Retrieved',
        //         ]);
        //     }
        // }
        // if ($request->has('sortby')) {
        //     $sortby = $request->sortby;
        //     $karyawan->orderBy($sortby, 'asc');
        // }

        return response()->json([
            'success' => true,
            'messages' => 'Data Retrieved Succesfully',
            'data' => $employee,
        ]);
    }

    public function showCompany()
    {
        $company = Companies::where('id', Auth::user()->profile->company_id)->first();
        $company['total_employee'] = Profiles::where('company_id', Auth::user()->profile->company_id)->count();
        $company['total_online'] = Profiles::where('company_id', Auth::user()->profile->company_id)->whereHas('user', function ($query) {
            $query->where('status', '1');
        })->count();

        return response()->json([
            'success' => true,
            'messages' => 'Data Retrieved Succesfully',
            'data' => $company,
        ]);
    }

    public function allEmployeeAccounts()
    {
        $users = User::whereHas(
            'profile',
            fn ($query) => $query
                ->where('company_id', Auth::user()->profile->company_id)
        )
            ->with('profile')
            ->latest()
            ->get();

        $index = $users->search(fn ($user) => $user->id == Auth::user()->id);
        $users->pull($index);
        $users = $users->values();

        return response()->json([
            'success' => true,
            'messages' => 'Data Retrieved Succesfully',
            'data' => $users,
        ]);
    }

    public function deleteKaryawan(User $user)
    {
        if ($user->role !== 1) {
            return response()->json([
                'success' => false,
                'messages' => 'The account cannot be deleted',
                'data' => ''
            ], 422);
        }

        $user->profile()->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'messages' => 'The account has been deleted'
        ]);
    }

    public function createKaryawan(Request $request)
    {
        $superuser = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'position' => 'required|string',
            'confirm_password' => 'required|required_with:password|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 1
        ]);

        $media = Media::create([
            'storage_path' => Media::DEFAULT_USER,
        ]);

        Profiles::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'position' => $request->position,
            'media_id' => $media->id,
            'company_id' => $superuser->profile->company_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'The account has been created',
        ]);
    }
}
