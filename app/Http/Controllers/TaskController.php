<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $category_id = $request->query('category_id');
        $search = $request->query('search');
        $perPage = $request->query('per_page', 5);
        $page = $request->query('page', 1);

        $request->validate([
            'filter' => 'in:all,deleted,not_deleted',
            'category_id' => 'integer',
            'search' => 'string',
            'per_page' => 'integer',
            'page' => 'integer',
        ]);

        $query = Task::latest()->with('sub_tasks')->with('category');

        // Filter by deletion status
        if ($filter === 'deleted') {
            $query->where('deleted', 1);
        } else if ($filter === 'not_deleted') {
            $query->where('deleted', 0);
        }

        // Filter by category if provided
        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $tasks = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        Task::create([
            'title' => $request->title,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'due_date' => $request->due_date,  // Add due_date to store
            'status' => $request->status,  // Add status to store
        ]);
        return response()->json(['message' => 'Task Created Successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'title' => $request->title,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
            'due_date' => $request->due_date,  // Add due_date to update
            'category_id' => $request->category_id,
            'status' => $request->status,  // Add status to update
        ]);
        return response()->json(['message' => 'Task Updated Successfully'], 200);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'deleted' => 1,
            'status' => 'Deleted'
        ]);
        return response()->json(['message' => 'Task Deleted Successfully'], 200);
    }

    public function retrieve($id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'deleted' => 0,
            'status' => 'Pending'
        ]);
        return response()->json(['message' => 'Task Retrieved Successfully'], 200);
    }
}
