<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Task;
use App\Models\Presences;

class DashboardController extends Controller
{
    public function index(User $user){
        $profile = Profiles::whereBelongsTo($user)->first();
        $task = Task::whereBelongsTo($user)->first();

        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => [$profile, $task],
        ]);
    }
}
