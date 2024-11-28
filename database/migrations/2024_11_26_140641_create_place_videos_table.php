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
        Schema::create('place_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('place_id')->nullable()->constrained('places')->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('youtube_url', 500)->nullable();
            $table->string('twitter_url', 500)->nullable();
            $table->string('twitter_video_url', 500)->nullable();
            $table->string('publisher')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_videos');
    }
};
