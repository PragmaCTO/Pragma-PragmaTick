<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    /**
     * Store a comment on a task.
     */
    public function store(Request $request, Task $task)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:1',
        ]);

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'content' => $validated['content'],
        ]);

        $user->logActivity('commented', "Added a comment on task {$task->code}", $task);

        return redirect()->back()->with('success', 'Comment posted successfully.');
    }
}
