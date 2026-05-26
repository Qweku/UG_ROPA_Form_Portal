<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('otp', 6);
            $table->string('email');
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_used']);
            $table->index('expires_at');
        });

        // Add email_verified_at column if not exists
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_verified']);
        });
    }
};
