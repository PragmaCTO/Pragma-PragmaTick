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
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('code')->nullable()->after('assigned_to');
            $table->string('type')->default('feature')->after('title'); // bug, feature, documentation, operation
            $table->date('start_date')->nullable()->after('status');
            $table->date('due_date')->nullable()->after('start_date');
            $table->foreignId('parent_id')->nullable()->after('milestone_id')->constrained('tasks')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['type', 'start_date', 'due_date', 'parent_id']);
        });
    }
};
