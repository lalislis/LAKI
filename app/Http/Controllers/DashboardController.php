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
    public function index()
    {
        $user = Auth::user();
        $profile = Profiles::whereBelongsTo($user)->first();
        $task = Task::whereBelongsTo($user)->latest()->get();
        $totalPresence = Presences::whereBelongsTo($user)->get()->count();

        $fromDate = Carbon::parse($profile->created_at);
        $toDate = Carbon::today();

        $totalDays = $fromDate->toDateString() === $toDate->toDateString()
            ? 1
            : ($fromDate->diffInDaysFiltered(fn (Carbon $date) => !$date->isWeekend(), $toDate)) + 1;

        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => collect($profile)->put('tasks', $task ?? [])
                ->put('email', $user->email)
                ->put('total_presences', $totalPresence)
                ->put('total_days', $totalDays),
        ]);
    }

    public function clockToday()
    {
        $user = Auth::user();
        $presence = Presences::whereBelongsTo($user)->whereDate('created_at', Carbon::today())
            ->with('media')
            ->first();

        if (!$presence) {
            Presences::create([
                'user_id' => $user->id,
                'media_id' => null,
                'clock_in' => null,
                'clock_out' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => $presence,
        ]);
    }
}
