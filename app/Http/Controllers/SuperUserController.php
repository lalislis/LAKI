<?php

namespace App\Http\Controllers;

use App\Models\{User, Profiles, Media, Task, Companies};
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuperUserController extends Controller
{
    public function index(Request $request){
        $karyawan = Profiles::whereHas('user', function($query){
            $query->where('role', '1');
        })
        ->where('company_id', Auth::user()->profile->company_id)
        ->with('user.tasks')
        ->with('media:id,storage_path')
        ->get()
        ->map
        ->only('id', 'media_id', 'name', 'position', 'user','media')
        ;
       
        if($request->has('search')){
            $search = $request->search;
            $karyawan = $karyawan = Profiles::whereHas('user', function($query){
                $query->where('role', '1');
            })
            ->where('company_id', Auth::user()->profile->company_id)
            ->where('name', 'like', '%'.$search.'%')
            ->with(['user.task', 'media:id,storage_path'])
            ->get()
            ->map
            ->only('id', 'media_id', 'name', 'position', 'user','media')
            ;
            if($karyawan->isEmpty()){
                return response()->json([
                    'success' => false,
                    'messages' => 'Data Cannot be Retrieved',
                ]);
            }
        }
        if($request->has('sortby')){
            $sortby = $request->sortby;
            $karyawan->orderBy($sortby, 'asc');
        }

        return response()->json([
            'success' => true,
            'messages' => 'Data Retrieved Succesfully',
            'data' => $karyawan   
        ]);
    }

    public function showCompany(Request $request){
        $company = Companies::where('id', Auth::user()->profile->company_id)->first();
        $company['total_employee'] = Profiles::where('company_id', Auth::user()->profile->company_id)->count();
        $company['total_online'] = Profiles::where('company_id', Auth::user()->profile->company_id)->whereHas('user', function($query){
            $query->where('status', '1');
        })->count();
        return response()->json([
            'success' => true,
            'messages' => 'Data Retrieved Succesfully',
            'data' => [
                'id' => $company->id,
                'name' => $company->name,
                'address' => $company->address,
                'phone' => $company->phone,
                'email' => $company->email,
                'website' => $company->website,
                'total_employee' => $company['total_employee'],
                'total_online' => $company['total_online'],
            ]
        ]);
    }

    public function showKaryawan(){
        $karyawan = User::where('id', Auth::user()->id)->with('profile')->first();
        if($karyawan->status == true){
            $status = 'Online';
        }
        else{
            $status = 'Offline';
        }
        return response()->json([
            'success' => true,
            'messages' => 'Data Retrieved Succesfully',
            'data' => [
                'id' => $karyawan->id, 
                'email' => $karyawan->email, 
                'profile' => [
                    'id' => $karyawan->profile->id,
                    'user_id' => $karyawan->profile->user_id,
                    'name' => $karyawan->profile->name,
                    'position' => $karyawan->profile->position,
                    'status' => $status
                ]]
        ]);
    }

    public function deleteKaryawan(User $user){
        Profiles::where('user_id', $user->id)->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'The account has been deleted'
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
            'success' => true,
            'message' => 'The account has been created',
        ]);
    }
}
