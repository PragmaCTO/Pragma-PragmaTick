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
        Schema::rename('task_comments', 'comments');

        Schema::table('comments', function (Blueprint $table) {
            if (\Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
                try {
                    $table->dropForeign('task_comments_task_id_foreign');
                } catch (\Throwable $e) {
                    // Foreign key may already be dropped
                }
            }
            $table->renameColumn('task_id', 'commentable_id');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->string('commentable_type')->default('App\\\\Models\\\\Task')->after('commentable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('commentable_type');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->renameColumn('commentable_id', 'task_id');
        });

        Schema::rename('comments', 'task_comments');
    }
};
