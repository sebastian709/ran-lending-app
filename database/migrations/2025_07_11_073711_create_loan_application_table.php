<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_application', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('loan_status')->nullable();
            $table->integer('load_step')->nullable();
            $table->unsignedBigInteger('loan_applicant')->nullable(); // user_id
            $table->string('purpose_of_loan')->nullable();
            $table->string('referral')->nullable(); // admin, friend, other

            $table->decimal('loan_amount', 15, 4)->nullable();
            $table->integer('loan_tenure')->nullable(); // in months or years
            $table->decimal('interest_rate', 5, 5)->nullable();
            $table->decimal('total_amount', 15, 4)->nullable();

            $table->string('payslip_img')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('upload_qr_code_img')->nullable();

            $table->unsignedBigInteger('government_type_id')->nullable();
            $table->string('government_id_img')->nullable();
            $table->string('billing_statement_img')->nullable();
            $table->string('signature_img')->nullable();

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_application');
    }
};
