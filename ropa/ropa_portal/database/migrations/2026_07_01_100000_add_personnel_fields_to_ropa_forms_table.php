<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ropa_forms', function (Blueprint $table) {
            $table->string('firstname')->nullable()->after('main_process_name');
            $table->string('surname')->nullable()->after('firstname');
            $table->string('personnel_id')->nullable()->after('surname');
            $table->string('role_responsible')->nullable()->after('personnel_id');
        });
    }

    public function down(): void
    {
        Schema::table('ropa_forms', function (Blueprint $table) {
            $table->dropColumn(['firstname', 'surname', 'personnel_id', 'role_responsible']);
        });
    }
};