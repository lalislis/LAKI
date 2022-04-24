<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Companies;

class AdminController extends Controller
{
    public function showSuperUser(){
        $superUser = User::where('role', '2')->with('profile')->get();

        return $superUser;
    }

    public function showCompanies(){
        $companies = Companies::all();

        return $companies;
    }

    public function registerSuperUser(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'title' => 'required',
            'company' => 'required',
            'password' => 'required',
            'confirm_password' => 'required_with:password|same:password'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => $request->password,
            'role' => "2"
        ]);

        $profile = Profiles::create([
            'name' => $request->name,
            'position' => $request->title,
            'company_id' => $request->company,
            'user_id' => $user->id,
            'media_id' => "1"
        ]);

        $data = Profiles::where('id', $profile->id)->first();

        return $data;
    }

    public function registerCompany(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'website' => 'required'
        ]);

        $company = Companies::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'website' => $request->website
        ]);

        $data = Companies::where('id', $company->id)->first();

        return $data;
    }
}
