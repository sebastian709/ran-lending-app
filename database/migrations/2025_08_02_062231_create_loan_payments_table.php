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
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_application_id')->index(); 
            $table->float('amount_sent');
            $table->integer('payment_status_id')->index(); 
            $table->integer('payment_type_id')->index(); 
            $table->text('reference_code')->index();
            $table->text('sent_to'); //account na sinendan 
            $table->text('remarks')->nullable();
            $table->text('attachment');
            $table->integer('added_by')->index(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};
