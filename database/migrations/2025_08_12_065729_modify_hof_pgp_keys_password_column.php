<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('hof_pgp_keys', 'key_password')) {
            return;
        }

        Schema::table('hof_pgp_keys', function (Blueprint $table) {
            $table->text('key_password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('hof_pgp_keys', function (Blueprint $table) {
            $table->string('key_password')->nullable()->change();
        });
    }
};
