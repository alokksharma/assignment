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
        Schema::create('world_languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country_id')->nullable()->index();
            $table->string('language_id')->nullable()->index();
            $table->string('api_id')->nullable()->index();
            $table->string('api_lang_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('world_languages');
    }
};
