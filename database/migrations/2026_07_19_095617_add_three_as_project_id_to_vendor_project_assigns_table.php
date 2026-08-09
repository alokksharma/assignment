<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_project_assigns', function (Blueprint $table) {
            $table->string('three_as_project_id')
                  ->nullable()
                  ->after('vendor_id')
                  ->comment('3AS Project ID');
        });
    }

    public function down(): void
    {
        Schema::table('vendor_project_assigns', function (Blueprint $table) {
            $table->dropColumn('three_as_project_id');
        });
    }
};
