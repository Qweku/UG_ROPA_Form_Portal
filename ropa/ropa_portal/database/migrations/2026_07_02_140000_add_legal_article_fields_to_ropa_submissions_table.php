<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ropa_submissions', function (Blueprint $table) {
            $table->json('cybersecurity_articles')->nullable()->after('gdpr_articles');
            $table->json('other_articles')->nullable()->after('cybersecurity_articles');
        });
    }

    public function down(): void
    {
        Schema::table('ropa_submissions', function (Blueprint $table) {
            $table->dropColumn(['cybersecurity_articles', 'other_articles']);
        });
    }
};