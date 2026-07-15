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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('project_id')->index();
            $table->string('project_code')->nullable();
            $table->string('project_name')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->string('category')->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('audience_type')->nullable();
            $table->enum('pii_collection', ['Yes', 'No'])->nullable();
            $table->enum('application_download', ['Yes', 'No'])->nullable();
            $table->enum('facial_coding', ['Yes', 'No'])->nullable();
            $table->unsignedTinyInteger('project_ir')->nullable();
            $table->unsignedSmallInteger('project_loi')->nullable();
            $table->decimal('cpi', 10, 2)->nullable();
            $table->unsignedInteger('quota')->default(0);
            $table->unsignedInteger('live_click_quota')->default(0);
            $table->unsignedInteger('test_click_quota')->default(0);
            $table->date('project_start_date')->nullable();
            $table->date('project_end_date')->nullable();
            $table->text('live_url')->nullable();
            $table->text('test_url')->nullable();
            $table->string('status')->nullable();
            $table->string('device_type')->nullable();
            $table->longText('target_spec')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
