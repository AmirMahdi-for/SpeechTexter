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
        Schema::create('speech_texter', function (Blueprint $table) {
            $table->id();
            $table->json('result')->nullable();
            $table->integer('response_status_code');
            $table->binary('file_data')->nullable();
            $table->string('file_url')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('file_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speech_texter');
    }
};
