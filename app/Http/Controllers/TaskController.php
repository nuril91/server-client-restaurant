<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|string|min:1',
            'action'    => 'nullable|string|in:send_file',
        ]);

        $task = Task::create([
            'client_id' => $validated['client_id'],
            'action'    => $validated['action'] ?? 'send_file',
            'status'    => 'pending',
        ]);

        return response()->json(['message' => 'Task created', 'task' => $task], 201);
    }

    public function poll(string $clientId)
    {
        $task = Task::where('client_id', $clientId)
            ->where('status', 'pending')
            ->orderBy('id')
            ->first();

        if (!$task) {
            return response()->json(['message' => 'no task']);
        }

        $task->update(['status' => 'in_progress']);

        return response()->json([
            'action'    => $task->action,
            'task_id'   => $task->id,
            'file_path' => 'storage/app/data/sample.txt',
        ]);
    }

    public function updateStatus(int $id, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,in_progress,completed,failed',
        ]);

        $task = Task::findOrFail($id);
        $task->update(['status' => $validated['status']]);

        return response()->json(['message' => 'Task status updated']);
    }
}
