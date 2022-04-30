<?php

namespace App\Http\Controllers;

use App\Models\{Task, User};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = User::whereHas(
            'profile.company',
            fn ($query) => $query->where('id', Auth::user()->profile->company_id)
        )
            ->with('tasks', fn ($query) => $query->whereDate('created_at', Carbon::today()))
            ->with('profile')
            ->get();

        $index = $tasks->search(fn ($task) => $task->id == Auth::user()->id);
        $self = $tasks->pull($index);
        $tasks = $tasks->prepend($self);

        return response()->json([
            'success' => true,
            'messages' => 'Data retrieved succesfully',
            'data' => $tasks,
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->except('_token', '_method');
        $req = $request->all();

        $validator = Validator::make($req, [
            'body' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validations' => $validator->errors(),
                'data' => ''
            ], 422);
        }

        $task = Task::where('user_id', Auth::user()->id)->whereDate('created_at', Carbon::today())->first();
        if (!$task) {
            $task = new Task;
            $task->user_id = Auth::user()->id;
            $task->title = Auth::user()->profile->name;
            $task->body = $request->body;
            $task->save();
        } else {
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
