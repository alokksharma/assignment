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
        Schema::table('vendor_project_assigns', function (Blueprint $table) {
            $table->string('project_vendor_id')
                ->nullable()
                ->after('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_project_assigns', function (Blueprint $table) {
            $table->dropColumn('project_vendor_id');
        });
    }
};
