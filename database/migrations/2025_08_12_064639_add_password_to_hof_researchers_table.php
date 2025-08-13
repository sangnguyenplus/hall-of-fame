<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('hof_researchers', 'password')) {
            return;
        }

        Schema::table('hof_researchers', function (Blueprint $table) {
            $table->string('password')->after('bio')->nullable();
            $table->timestamp('email_verified_at')->after('password')->nullable();
            $table->rememberToken()->after('email_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('hof_researchers', function (Blueprint $table) {
            $table->dropColumn(['password', 'email_verified_at', 'remember_token']);
        });
    }
};
