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
        Schema::table('proofs', function (Blueprint $table) {
            $table->string('reconcile_status')
                  ->nullable()
                  ->after('status'); // Change 'status' to the column after which you want it
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proofs', function (Blueprint $table) {
            $table->dropColumn('reconcile_status');
        });
    }
};
