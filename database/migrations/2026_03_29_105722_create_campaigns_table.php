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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('type')->default('video'); // video, clip
            $table->integer('slots')->default(0);
            $table->string('thumbnail')->nullable();
            
            // Brief & Instructions
            $table->text('desc')->nullable();
            $table->longText('full_brief')->nullable();
            $table->text('donts')->nullable();
            $table->string('assets_url')->nullable();
            
            // Constraints & Targets
            $table->date('deadline')->nullable();
            $table->string('video_length', 50)->nullable();
            $table->string('link')->nullable(); // Target URL 
            $table->string('platform')->default('all');
            
            // Budget & Payments
            $table->bigInteger('budget')->default(0);
            $table->integer('price_per_1k')->default(0);
            
            // Status: draft, active, completed, cancelled
            $table->string('status')->default('draft');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
