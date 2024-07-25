<?php

// app/Http/Controllers/SubTaskController.php

namespace App\Http\Controllers;

use App\Models\SubTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubTaskController extends Controller
{
    public function store($task_id, Request $request) {
        $subTasks = $request->all();

        foreach($subTasks as $subTask) {
            SubTask::create([
                'task_id' => $task_id,
                'title' => $subTask['title'],
                'description' => $subTask['description'],
                'start_date' => $subTask['start_date'],
                'end_date' => $subTask['end_date'],
            ]);
        }
        return response()->json('Sub Task Stored Successfully');
    }

    public function update($task_id, $subTask_id, Request $request) {
        $subTask = SubTask::where('id', $subTask_id);
        $subTask->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        $subTasks = SubTask::where('task_id', $task_id)->get();
        return response()->json($subTasks);
    }

    public function destroy($task_id, $subTask_id) {
        $subTask = SubTask::where('id', $subTask_id);
        $subTask->delete();

        $subTasks = SubTask::where('task_id', $task_id)->get();
        return response()->json($subTasks);
    }

    public function markAsComplete($task_id, $subTask_id) {
        $subTask = SubTask::where('id', $subTask_id);
        $subTask->update([
            'status' => 1,
        ]);

        $subTasks = SubTask::where('task_id', $task_id)->get();
        return response()->json($subTasks);
    }

    public function markAsIncomplete($task_id, $subTask_id) {
        $subTask = SubTask::where('id', $subTask_id);
        $subTask->update([
            'status' => 0,
        ]);

        $subTasks = SubTask::where('task_id', $task_id)->get();
        return response()->json($subTasks);
    }
}

