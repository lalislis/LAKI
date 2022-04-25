<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profiles;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $karyawan = User::where('role', '1')->with('profile')->get();
        if($request->has('search')){
            $search = $request->search;
            $karyawan = User::where('role', '1')->where('email', 'like', '%'.$search.'%')->with('profile')->get();
            if($karyawan->isEmpty()){
                return response()->json([
                    'status' => false,
                    'messages' => 'Data Karyawan Tidak Ditemukan',
                ]);
            }
        }
        if($request->has('sortby')){
            $sortby = $request->sortby;
            $karyawan->orderBy($sortby, 'asc');
        }

        return response()->json([
            'status' => true,
            'messages' => 'Data Karyawan Berhasil Ditampilkan',
            'data' => $karyawan   
        ]);
    }
}
