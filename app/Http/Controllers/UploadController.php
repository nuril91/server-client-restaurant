<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Task;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|string',
            'task_id'   => 'required|integer|exists:tasks,id',
            'file'      => 'required|file',
        ]);

        $filename = $validated['client_id'].'_uploaded_'.time().'_'.$request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('uploads', $filename);

        $task = Task::findOrFail($validated['task_id']);
        $task->update(['status' => 'completed', 'file_path' => $path]);

        return response()->json(['message' => 'File received successfully', 'path' => $path, 'task' => $task]);
    }
}
