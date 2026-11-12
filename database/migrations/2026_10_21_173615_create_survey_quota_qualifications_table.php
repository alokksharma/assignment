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
        Schema::create('survey_quota_qualifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id')->nullable()->index();
            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->unsignedBigInteger('question_id')->nullable()->index();
            $table->string('answer_code')->nullable();
            $table->string('answer_text')->nullable();
            $table->unsignedInteger('total_qualification_count')->default(0);
            $table->unsignedInteger('total_quota_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_quota_qualifications');
    }
};
