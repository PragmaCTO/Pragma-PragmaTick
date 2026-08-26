<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumns('tasks', ['project_id', 'status', 'deleted_at'])) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->index(['project_id', 'status', 'deleted_at'], 'idx_tasks_perf');
            });
        }

        if (Schema::hasColumns('comments', ['commentable_type', 'commentable_id', 'deleted_at'])) {
            Schema::table('comments', function (Blueprint $table) {
                $table->index(['commentable_type', 'commentable_id', 'deleted_at'], 'idx_comments_perf');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropIndex('idx_tasks_perf');
            });
        }

        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropIndex('idx_comments_perf');
            });
        }
    }
};
