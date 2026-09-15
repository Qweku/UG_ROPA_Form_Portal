<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First, add the new columns
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname')->nullable()->after('name');
            $table->string('surname')->nullable()->after('firstname');
        });

        // Split existing names into firstname and surname
        $users = User::all();
        foreach ($users as $user) {
            $nameParts = explode(' ', $user->name, 2);
            $user->firstname = $nameParts[0] ?? '';
            $user->surname = $nameParts[1] ?? '';
            $user->save();
        }

        // Now make the columns required after data migration
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname')->nullable(false)->change();
            $table->string('surname')->nullable(false)->change();
        });

        // Finally, drop the old name column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        // Re-add the name column
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
        });

        // Combine firstname and surname back into name
        $users = User::all();
        foreach ($users as $user) {
            $user->name = trim($user->firstname.' '.$user->surname);
            $user->save();
        }

        // Drop the new columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['firstname', 'surname']);
        });
    }
};
