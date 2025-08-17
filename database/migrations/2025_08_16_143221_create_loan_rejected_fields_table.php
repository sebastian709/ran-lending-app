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
         Schema::create('loan_rejected_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id'); // link to loans table

            // Rejected fields
            $table->decimal('amount_amount', 15, 2)->nullable();
            $table->decimal('amount_suggested', 15, 2)->nullable();

            $table->string('payslip_img')->nullable();              // proof of income
            $table->string('upload_qr_code_img')->nullable();
            $table->string('government_id_img')->nullable();
            $table->string('billing_statement_img')->nullable();    // supporting doc

            $table->timestamp('rejected_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_rejected_fields');
    }
};
