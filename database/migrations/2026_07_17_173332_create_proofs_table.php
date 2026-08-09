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
        Schema::create('proofs', function (Blueprint $table) {
            $table->id();
            $table->string('3ASProjectID');
            $table->string('ClientProjectID');
            $table->string('ccode');
            $table->string('encode');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('link');
            $table->tinyInteger('status')->default(0)->comment('0=Pending, 1=Active, 2=Completed, 3=Rejected, 4=Paused, 5=Closed');
            $table->string('clientsurveystatus')->nullable();
            $table->string('client_url')->nullable();
            $table->string('device')->nullable()->comment('Desktop/Mobile');
            $table->string('ip_address', 45)->nullable();
            $table->text('useragent')->nullable();
            $table->string('country_id')->nullable();
            $table->string('cityname')->nullable();
            $table->decimal('geolat', 10, 7)->nullable();
            $table->decimal('geolong', 10, 7)->nullable();
            $table->string('notes')->nullable();
            $table->text('asr_hash')->nullable();
            $table->text('asr_hash_short')->nullable();
            $table->integer('fraud_score')->nullable();
            $table->text('fraud_reason')->nullable();
            $table->boolean('is_fraud')->nullable();
            $table->integer('ip_attempts')->nullable();
             $table->boolean('ua_is_bot')->nullable();
            $table->boolean('country_mismatch')->nullable();
            $table->boolean('duplicate_pcode')->nullable();
            $table->timestamp('fraud_created_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proofs');
    }
};
