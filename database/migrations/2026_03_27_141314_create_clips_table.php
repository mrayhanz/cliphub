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
        Schema::create('clips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Source & Video Metadata
            $table->string('title');
            $table->text('hook')->nullable();
            $table->string('source_url');
            $table->string('video_id')->nullable();        // YouTube video ID
            $table->string('ratio')->default('9:16');      // Aspect ratio (e.g. 9:16, 16:9)

            // Clip Timing (in seconds)
            $table->integer('start_time')->default(0);
            $table->integer('end_time')->default(0);
            $table->string('duration')->nullable();

            // AI & Caption Settings
            $table->boolean('has_captions')->default(false);
            $table->text('transcript')->nullable();
            $table->integer('score')->default(0);

            // Output File
            $table->string('status')->default('queued'); // queued, processing, done, failed
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clips');
    }
};
