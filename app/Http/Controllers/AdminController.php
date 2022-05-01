<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Companies;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getSuperUser(){
        $superUser = User::where('role', '2')->with('profile','tasks')->get();
        
        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => $superUser,
        ]);
    }

    public function registerSuperUser(Request $request){
        $req = $request->all();

        $validator = Validator::make($req, [
            'name' => ['required', 'string'],
            'email' => ['required', 'string'],
            'position' => ['required', 'string'],
            'password' => ['required', 'string'],
            'confirm_password' => ['required', 'string', 'same:password'],
            'company_id' => ['required', 'int'],
            'role' => ['required', 'int']
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        $profile = Profiles::create([
            'name' => $request->name,
            'position' => $request->position,
            'company_id' => $request->company_id,
            'user_id' => $user->id,
            'media_id' => "1"
        ]);

        return response()->json([
            'success' => true,
            'messages' => 'The account has been created'
        ]);
    }

    public function deleteSuperUser(User $user){
        $user->delete();
        return response()->json([
            'success' => true,
            'messages' => 'Success Delete Data',
        ]);
    }

    public function registerCompany(Request $request){
        $data = $request->except('_token', '_method');
        $req = $request->all();

        $validator = Validator::make($req, [
            'company_id' => ['required', 'int'],
            'name' => ['required', 'string'],
            'email' => ['required', 'string'],
            'address' => ['required', 'string'],
            'website' => ['required', 'string'],
            'phone' => ['required', 'string', 'same:password'],
        ]);

        if($request->compani_id == NULL){
            $company = Companies::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'website' => $request->website,
                'media_id' => '1'
            ]);
        }
        else{
            $company = Companies::where('id', $request->company_id)->first();
            $company->update($data);
        }

        return response()->json([
            'success' => true,
            'messages' => 'The company has been created and updated',
        ]);
    }

    public function listCompanies(){
        $companies = Companies::get();

        $namecompanies = $companies->map(function ($company) {
            return collect($company->toArray())
                ->only(['name'])
                ->all();
        });

        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => $namecompanies,
        ]);
    }

    public function getCompanies(){
        $companies = Companies::get();

        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => $companies,
        ]);
    }
}
