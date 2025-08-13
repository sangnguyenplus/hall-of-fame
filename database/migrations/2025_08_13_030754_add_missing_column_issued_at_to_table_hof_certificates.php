<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('hof_certificates', function (Blueprint $table) {
            $table->date('issued_at')->nullable()->after('acknowledgment_date');
            $table->string('status', 60)->default('pending')->after('is_encrypted');
        });
    }

    public function down(): void
    {
        Schema::table('hof_certificates', function (Blueprint $table) {
            $table->dropColumn('issued_at');
            $table->dropColumn('status');
        });
    }
};
