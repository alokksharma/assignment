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
        Schema::create('demographics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('world_language_id')->nullable();
            $table->string('demographic_name')->nullable();
            $table->unsignedBigInteger('question_id')->nullable();
            $table->text('question_text')->nullable();
            $table->string('question_type')->nullable();
            $table->json('answer_codes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demographics');
    }
};
