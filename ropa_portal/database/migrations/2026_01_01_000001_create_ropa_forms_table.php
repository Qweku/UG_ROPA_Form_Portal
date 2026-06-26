<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ropa_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
           $table->foreignId('college_id')->nullable()->constrained();
            $table->string('business_function');        // School/Dept
            $table->string('main_process_name');
            $table->boolean('all_submissions_completed')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ropa_forms');
    }
};
