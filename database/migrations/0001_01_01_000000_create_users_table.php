<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('can_use_gpmc')->default(false);
            $table->text('gpmc_auth_data')->nullable();
            $table->text('gpmc_state_token')->nullable();
            $table->text('gpmc_bearer_token')->nullable();
            $table->bigInteger('gpmc_bearer_expiry')->nullable();
            $table->json('gpmc_import_progress')->nullable();
            $table->json('player_settings')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->string('api_key', 64)->nullable()->unique();
            // Sync tracking
            $table->boolean('sync_in_progress')->default(false);
            $table->timestamp('last_sync_at')->nullable();
            $table->boolean('sync_queued')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
