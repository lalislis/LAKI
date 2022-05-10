<?php

namespace App\Http\Controllers;

use App\Models\{User, Profiles, Media};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $employees = Profiles::where('company_id', Auth::user()->profile->company_id)
            ->with('user')
            ->get()
            ->sortByDesc(fn ($query) => $query->user->status)
            ->values();

        $index = $employees->search(fn ($employee) => $employee->user_id == Auth::user()->id);
        $self = $employees->pull($index);
        $employees = $employees->prepend($self);

        // $karyawan = Profiles::whereHas('user', function ($query) {
        //     $query->where('role', '1');
        // })
        //     ->where('company_id', Auth::user()->profile->company_id)
        //     ->with('user:id,status')
        //     ->with('media:id,storage_path')
        //     ->get()
        //     ->map
        //     ->only('id', 'media_id', 'name', 'position', 'user', 'media');

        // if ($request->has('search')) {
        //     $search = $request->search;
        //     $karyawan = Profiles::whereHas('user', function ($query) {
        //         $query->where('role', '1');
        //     })
        //         ->where('company_id', Auth::user()
        //             ->profile->company_id)
        //         ->where('name', 'like', '%' . $search . '%')
        //         ->with('user:id,status')
        //         ->with('media:id,storage_path')
        //         ->get()
        //         ->map
        //         ->only('id', 'media_id', 'name', 'position', 'user', 'media');

        //     if ($karyawan->isEmpty()) {
        //         return response()->json([
        //             'success' => false,
        //             'messages' => 'Data Failed to be Retrieved',
        //         ]);
        //     }
        // }

        // if ($request->has('sortby')) {
        //     $sortby = $request->sortby;
        //     $karyawan->orderBy($sortby, 'asc');
        // }

        return response()->json([
            'success' => true,
            'messages' => 'Data Retrieved Successfully',
            'data' => $employees
        ]);
    }
}
