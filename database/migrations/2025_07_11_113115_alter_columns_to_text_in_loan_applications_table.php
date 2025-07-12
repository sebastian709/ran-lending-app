<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('loan_application', function (Blueprint $table) {
            $table->text('purpose_of_loan')->nullable()->change();
            $table->text('payslip_img')->nullable()->change();
            $table->text('upload_qr_code_img')->nullable()->change();
            $table->text('government_id_img')->nullable()->change();
            $table->text('billing_statement_img')->nullable()->change();
            $table->text('signature_img')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            // $table->string('purpose_of_loan')->nullable()->change();
            // $table->string('payslip_img')->nullable()->change();
            // $table->string('upload_qr_code_img')->nullable()->change();
            // $table->string('government_id_img')->nullable()->change();
            // $table->string('billing_statement_img')->nullable()->change();
            // $table->string('signature_img')->nullable()->change();
        });
    }
};
