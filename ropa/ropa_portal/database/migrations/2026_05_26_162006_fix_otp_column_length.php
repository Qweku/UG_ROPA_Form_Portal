<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Change OTP column to be longer to accommodate hashed values
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->string('otp', 255)->change();
        });
    }

    public function down(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->string('otp', 6)->change();
        });
    }
};
