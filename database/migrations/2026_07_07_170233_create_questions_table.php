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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('language_id');
            $table->integer('control_type_id');
            $table->string('title')->nullable();
            $table->text('question')->nullable();
            $table->text('mapped_options')->nullable();
             $table->string('from_range')->nullable();
            $table->string('to_range')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
