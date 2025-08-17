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
        Schema::table('loan_rejected_fields', function (Blueprint $table) {
            // Rename column amount_amount → loan_amount
            $table->renameColumn('amount_amount', 'loan_amount');

            // Modify columns (set default 0, make integer)
            $table->integer('loan_amount')->default(0)->change();
            $table->integer('payslip_img')->default(0)->change();
            $table->integer('upload_qr_code_img')->default(0)->change();
            $table->integer('government_id_img')->default(0)->change();
            $table->integer('billing_statement_img')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
