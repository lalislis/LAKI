<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Companies;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getSuperUser()
    {
        $superUser = User::where('role', 2)
            ->latest()
            ->with('profile')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => $superUser,
        ]);
    }

    public function registerSuperUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'position' => 'required|string',
            'password' => 'required|string',
            'confirm_password' => 'required|string|same:password',
            'company_id' => 'required|integer|exists:companies,id',
            'role' => 'required|integer|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }

        $media = Media::create([
            'storage_path' => Media::DEFAULT_USER,
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        Profiles::create([
            'name' => $request->name,
            'position' => $request->position,
            'company_id' => $request->company_id,
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);

        return response()->json([
            'success' => true,
            'messages' => 'The account has been created'
        ]);
    }

    public function deleteSuperUser(User $user)
    {
        if ($user->role !== 2) {
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
            'messages' => 'The account has been deleted',
        ]);
    }

    public function registerCompany(Request $request)
    {
        $data = $request->except('_token', '_method');

        $emailRules = !$request->has('company_id') ?
            '' :
            ',email,' . $request->company_id;

        $validator = Validator::make($request->all(), [
            'company_id' => 'integer',
            'name' => 'required|string',
            'email' => "required|email|unique:companies$emailRules",
            'address' => 'required|string',
            'website' => 'required|string',
            'phone' => 'required|string',
            'logo' => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }

        if (!$request->has('company_id')) {
            $media = Media::create([
                'storage_path' => $request->has('logo')
                    ? $request->file('logo')->store('images', 'public') : Media::DEFAULT_COMPANY,
            ]);

            $company = Companies::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'website' => $request->website,
                'media_id' => $media->id,
            ]);
        } else {
            $company = Companies::where('id', $request->company_id)->first();
            $company->update([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'website' => $request->website,
            ]);

            if ($request->has('logo')) {
                $path = $request->file('logo')->store('images', 'public');
                $company->media()->update([
                    'storage_path' => $path,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'messages' => 'The company has been created or updated',
        ]);
    }

    public function listCompanies()
    {
        $companies = Companies::get();

        $listCompanies = $companies->map(function ($company) {
            return collect($company->toArray())
                ->only(['id', 'name'])
                ->all();
        });

        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => $listCompanies,
        ]);
    }

    public function getCompanies()
    {
        $companies = Companies::latest()->get();

        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => $companies,
        ]);
    }
}
