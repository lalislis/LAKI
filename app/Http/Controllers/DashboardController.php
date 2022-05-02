<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Task;
use App\Models\Presences;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();
        $profile = Profiles::whereBelongsTo($user)->first();
        $task = Task::whereBelongsTo($user)->first();

        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => [$profile, $task],
        ]);
    }

    public function clockToday(){
        $user = Auth::user();
        $profile = Profiles::whereBelongsTo($user)->first();
        $presence = Presences::whereBelongsTo($user)->whereDate('created_at', Carbon::today())->firstOrCreate([
            'user_id' => $user->id,
            'media_id' => $profile->media_id
        ]);
        $presence->clock_in = $presence->updated_at;
        $presence->save();

        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => $presence,
        ]);
    }
}
