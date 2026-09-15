<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ropa_forms', function (Blueprint $table) {
            // Null = not yet decided (still on step 1 of the very first submission).
            // Once set (true/false) on the first submission, it is locked for the
            // lifetime of this RopaForm and drives the branching question at step 14.
            $table->boolean('has_sub_processes')->nullable()->after('main_process_name');
        });
    }

    public function down(): void
    {
        Schema::table('ropa_forms', function (Blueprint $table) {
            $table->dropColumn('has_sub_processes');
        });
    }
};
