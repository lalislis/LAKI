<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Presences;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PresencesController extends Controller
{
    public function clockIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }

        $media = Media::create([
            'storage_path' => $request->file('photo')->store('images', 'public')
        ]);

        $presence = Presences::whereBelongsTo(Auth::user())->whereDate('created_at', Carbon::today());
        $presence->update([
            'clock_in' => Carbon::now(),
            'media_id' => $media->id
        ]);

        return response()->json([
            "success" => true,
            "messages" => "You have been clocked in succesfully, don't forget to clock out",
        ]);
    }

    public function clockOut()
    {
        $presence = Presences::whereBelongsTo(Auth::user())->whereDate('created_at', Carbon::today());
        $presence->update([
            'clock_out' => Carbon::now(),
        ]);

        return response()->json([
            "success" => true,
            "messages" => "You have been clocked out succesfully",
        ]);
    }
}
