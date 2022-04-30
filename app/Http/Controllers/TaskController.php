<?php

namespace App\Http\Controllers;

use App\Models\{Task, User, Profiles};
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $task = Task::whereHas('user.profile', function($query) {
            $query->where('company_id', Auth::user()->profile->company_id);
        })->whereDate('created_at', Carbon::today())->get();
        if(!task){
            return response()->json([
                'success' => false,
                'message' => 'Data task tidak ditemukan'
            ]);
        }
        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => $task,
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->except('_token','_method');
        $task = Task::where('user_id', Auth::user()->id)->whereDate('created_at', Carbon::today())->first();
        if(!$task){
            $task = new Task;
            $task->user_id = Auth::user()->id;
            $task->title = $request->title;
            $task->body = $request->body;
            $task->save();
        }
        else{
            $task->update($data);
        }

        return response()->json([
            'success' => true,
            'messages' => 'Your task has been updated succesfully',
            'data' => $data,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json([
            'success' => true,
            'messages' => 'Success Delete Data',
        ]);
    }
}
