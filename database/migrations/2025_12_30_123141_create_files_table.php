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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('slug')->unique();
            $table->bigInteger('file_size');
            $table->string('media_id');
            $table->bigInteger('timestamp');
            $table->text('media_url');
            $table->string('dedup_id')->unique();
            $table->string('resolution')->nullable();
            $table->string('rip_type')->nullable();
            $table->string('codec')->nullable();
            $table->timestamps();
        });

        // Add FULLTEXT index for fast searching
        DB::statement('ALTER TABLE files ADD FULLTEXT INDEX files_filename_fulltext (filename)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
