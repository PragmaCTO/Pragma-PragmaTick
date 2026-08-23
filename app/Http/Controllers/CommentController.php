<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Task;
use App\Models\Project;
use App\Models\ChecklistItem;

class CommentController extends Controller
{
    /**
     * Store a comment on a commentable model.
     */
    public function store(Request $request, $type, $id)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:1',
        ]);

        $modelClass = match($type) {
            'task' => Task::class,
            'project' => Project::class,
            'checklist' => ChecklistItem::class,
            default => abort(404, 'Invalid comment type'),
        };

        $model = $modelClass::findOrFail($id);

        $comment = $model->comments()->create([
            'user_id' => $user->id,
            'content' => $validated['content'],
        ]);

        // Activity Logging logic
        $logMessage = match($type) {
            'task' => "Added a comment on task {$model->code}",
            'project' => "Added a comment on project {$model->name}",
            'checklist' => "Added a comment on checklist item '{$model->title}'",
            default => 'Added a comment',
        };
        $user->logActivity('commented', $logMessage, $model);

        if ($request->wantsJson()) {
            $comment->load('user');
            return response()->json([
                'success' => true,
                'comment' => $comment
            ]);
        }

        return redirect()->back()->with('success', 'Comment posted successfully.');
    }
    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate(['content' => 'required|string|min:1']);
        $comment->update(['content' => $validated['content']]);
        
        auth()->user()->logActivity('updated', "Updated their comment", $comment->commentable);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Comment updated.');
    }

    public function destroy(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        $commentable = $comment->commentable;
        $comment->delete();
        
        auth()->user()->logActivity('deleted', "Deleted a comment", $commentable);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Comment deleted.');
    }
}
