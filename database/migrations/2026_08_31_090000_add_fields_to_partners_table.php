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
        Schema::table('partners', function (Blueprint $table) {
            $table->string('partner_nick_name')->nullable()->after('user_id');
            $table->string('partner_api_url')->nullable()->after('partner_nick_name');
            $table->string('status')->default('Active')->after('is_active');
            $table->timestamp('expired_at')->nullable()->after('status');
            $table->string('complete_redirect')->nullable()->after('expired_at');
            $table->string('terminate_redirect')->nullable()->after('complete_redirect');
            $table->string('quotafull_redirect')->nullable()->after('terminate_redirect');
            $table->string('quality_redirect')->nullable()->after('quotafull_redirect');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn([
                'partner_nick_name',
                'partner_api_url',
                'status',
                'expired_at',
                'complete_redirect',
                'terminate_redirect',
                'quotafull_redirect',
                'quality_redirect',
            ]);
        });
    }
};
