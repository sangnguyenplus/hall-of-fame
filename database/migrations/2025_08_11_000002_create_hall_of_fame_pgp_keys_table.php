<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('hof_pgp_keys')) {
            return;
        }

        Schema::create('hof_pgp_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key_name');
            $table->string('key_id')->unique();
            $table->text('public_key');
            $table->text('private_key')->nullable();
            $table->string('key_password')->nullable();
            $table->string('key_email');
            $table->string('key_fingerprint');
            $table->date('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('can_sign')->default(true);
            $table->boolean('can_encrypt')->default(true);
            $table->json('key_info')->nullable();
            $table->timestamps();

            $table->index(['key_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hof_pgp_keys');
    }
};
