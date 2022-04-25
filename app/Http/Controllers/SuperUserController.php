<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Profiles;
use Auth;
use Illuminate\Http\Request;

class SuperUserController extends Controller
{
    public function index(Request $request){
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

    public function showKaryawan(User $user){
        $karyawan = User::where('id', $user->id)->with('profile')->first();

        return response()->json([
            'status' => true,
            'messages' => 'Data Karyawan Berhasil Ditampilkan',
            'data' => $karyawan   
        ]);
    }

    public function showTask(User $user){
        $task = Task::where('user_id', $user->id)->get();

        return response()->json([
            'status' => true,
            'messages' => 'Data Task Berhasil Ditampilkan',
            'data' => $task  
        ]);
    }

    public function deleteKaryawan(User $user){
        Profiles::where('user_id', $user->id)->delete();
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Karyawan berhasil dihapus'
        ]);
    }

    public function createKaryawan(Request $request){
        $superuser = Auth::user();
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'position' => 'required',
            'confirm_password' => 'required_with:password|same:password'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 1
        ]);

        $profile = Profiles::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'position' => $request->position,
            'media_id' => '1',
            'company_id' => $superuser->profile->company_id
        ]);

        $data = Profiles::where('id', $profile->id)->first();
        return response()->json([
            'status' => true,
            'message' => 'Karyawan berhasil ditambahkan',
            'data' => $data
        ]);
    }
}
