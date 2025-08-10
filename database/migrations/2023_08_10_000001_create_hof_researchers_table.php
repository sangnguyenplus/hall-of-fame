<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hof_researchers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('github')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        Schema::table('vulnerability_reports', function (Blueprint $table) {
            $table->foreignId('researcher_id')->nullable()
                ->after('id')
                ->constrained('hof_researchers')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('vulnerability_reports', function (Blueprint $table) {
            $table->dropForeign(['researcher_id']);
            $table->dropColumn('researcher_id');
        });

        Schema::dropIfExists('hof_researchers');
    }
};
