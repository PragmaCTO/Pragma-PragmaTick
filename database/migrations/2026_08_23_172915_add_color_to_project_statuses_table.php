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
        Schema::table('project_statuses', function (Blueprint $table) {
            if (!Schema::hasColumn('project_statuses', 'color')) {
                $table->string('color')->default('#008b8b')->after('slug');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_statuses', function (Blueprint $table) {
            if (Schema::hasColumn('project_statuses', 'color')) {
                $table->dropColumn('color');
            }
        });
    }
};
