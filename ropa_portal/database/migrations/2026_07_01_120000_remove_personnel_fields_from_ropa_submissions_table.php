<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ropa_submissions', function (Blueprint $table) {
            $table->dropColumn(['firstname', 'surname', 'personnel_id', 'role_responsible']);
        });
    }

    public function down(): void
    {
        Schema::table('ropa_submissions', function (Blueprint $table) {
            $table->string('surname')->nullable();
            $table->string('firstname')->nullable();
            $table->string('personnel_id')->nullable();
            $table->string('role_responsible')->nullable();
        });
    }
};
