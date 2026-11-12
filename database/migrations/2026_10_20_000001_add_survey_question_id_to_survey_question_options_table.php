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
        Schema::table('survey_question_options', function (Blueprint $table) {
            if (! Schema::hasColumn('survey_question_options', 'survey_question_id')) {
                $table->unsignedBigInteger('survey_question_id')->nullable()->after('id');
                $table->index('survey_question_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_question_options', function (Blueprint $table) {
            if (Schema::hasColumn('survey_question_options', 'survey_question_id')) {
                $table->dropColumn('survey_question_id');
            }
        });
    }
};
