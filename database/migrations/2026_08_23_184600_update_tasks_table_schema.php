<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('priority')->default('medium')->after('description');
            $table->date('start_date')->nullable()->after('status');
        });

        DB::statement("ALTER TABLE tasks MODIFY COLUMN type VARCHAR(50) NOT NULL DEFAULT 'feature'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['priority', 'start_date']);
        });
        
        DB::statement("ALTER TABLE tasks MODIFY COLUMN type ENUM('task','subtask','bug','issue','epic','improvement') NOT NULL DEFAULT 'task'");
    }
};
