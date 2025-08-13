<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('hof_certificates')) {
            return;
        }

        Schema::create('hof_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vulnerability_report_id');
            $table->string('certificate_id')->unique();
            $table->string('researcher_name');
            $table->string('researcher_email');
            $table->string('vulnerability_title');
            $table->string('vulnerability_type');
            $table->date('discovery_date');
            $table->date('acknowledgment_date');
            $table->text('description')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('signed_pdf_path')->nullable();
            $table->text('pgp_signature')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->boolean('is_encrypted')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('vulnerability_report_id')->references('id')->on('vulnerability_reports')->onDelete('cascade');
            $table->index(['certificate_id', 'researcher_email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hof_certificates');
    }
};
