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
        // database/migrations/xxxx_xx_xx_create_invoices_table.php
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique()->nullable();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('currency')->default('USD');
            $table->string('status')->default('Draft'); // Draft, Sent, Paid
            $table->string('service_location');
            $table->unsignedInteger('supplier_id')->nullable();
           // $table->text('billing_address')->nullable();
            $table->string('tax')->nullable();
            // $table->string('project')->nullable();
            $table->decimal('subtotal', 12, 2)->nullable();
            $table->decimal('discount', 12, 2)->nullable();
            $table->decimal('cgst', 12, 2)->nullable();
            $table->decimal('sgst', 12, 2)->nullable();
            $table->decimal('igst', 12, 2)->nullable();
            $table->decimal('other_tax', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->decimal('amount_paid', 12, 2)->nullable();
            $table->decimal('balance_due', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('invoice_id')->nullable();
            $table->string('description')->nullable();
            $table->string('billing_type')->nullable(); // API Usage, Custom, etc.
            $table->decimal('quantity', 15, 2)->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
