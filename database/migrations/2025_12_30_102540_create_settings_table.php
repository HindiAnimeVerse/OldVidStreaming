<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        $defaults = [
            ['key' => 'site_name', 'value' => 'VidStreaming'],
            ['key' => 'site_description', 'value' => 'Unlimited Video Hosting Platform'],
            ['key' => 'site_favicon', 'value' => '/branding/favicon.svg'],
            ['key' => 'allow_registration', 'value' => '1'],
            ['key' => 'email_verification', 'value' => '0'],
            ['key' => 'dmca_enabled', 'value' => '1'],
            ['key' => 'maintenance_mode', 'value' => '0'],
        ];

        foreach ($defaults as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
