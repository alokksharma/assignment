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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('code')->nullable()->after('name');
            $table->string('url')->nullable()->after('code');
            $table->string('supplier_token')->nullable()->after('url');
            $table->string('supplier_id')->nullable()->after('supplier_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'url',
                'supplier_token',
                'supplier_id',
            ]);
        });
    }
};
