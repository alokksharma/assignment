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
        Schema::create('vendor_project_assigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('survey_id');
            $table->string('temp_complete')->nullable();
            $table->string('temp_terminate')->nullable();
            $table->string('temp_quotafull')->nullable();
            $table->string('temp_speedster')->nullable();
            $table->string('temp_cpi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_project_assigns');
    }
};
