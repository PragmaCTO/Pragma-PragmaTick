<?php

namespace App\Observers;

class WikiObserver
{
    public function created($model): void
    {
        $user = auth()->user();
        if (!$user) return;
        
        $class = class_basename($model);
        if ($class === 'WikiBook') {
            $user->logActivity('created', "Created Wiki Book '{$model->title}'", $model);
        } elseif ($class === 'WikiChapter') {
            $bookTitle = $model->book ? $model->book->title : 'Unknown';
            $user->logActivity('created', "Created Wiki Chapter '{$model->title}' in book {$bookTitle}", $model);
        } elseif ($class === 'WikiPage') {
            $user->logActivity('created', "Created Wiki Page '{$model->title}'", $model);
        }
    }

    public function updated($model): void
    {
        $user = auth()->user();
        if (!$user) return;
        
        $class = class_basename($model);
        if ($class === 'WikiPage') {
            $user->logActivity('updated', "Updated Wiki Page '{$model->title}'", $model);
        }
    }

    public function deleted($model): void
    {
        $user = auth()->user();
        if (!$user) return;
        
        $class = class_basename($model);
        if ($class === 'WikiBook') {
            $user->logActivity('deleted', "Soft-deleted Wiki Book '{$model->title}'", $model);
        } elseif ($class === 'WikiChapter') {
            $user->logActivity('deleted', "Soft-deleted Wiki Chapter '{$model->title}'", $model);
        } elseif ($class === 'WikiPage') {
            $user->logActivity('deleted', "Soft-deleted Wiki Page '{$model->title}'", $model);
        }
    }
}
