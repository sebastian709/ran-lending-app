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
        Schema::create('loan_payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_tenure_id')->index();
            $table->text('reference_code')->index();
            $table->text('remarks');
            $table->text('payment');
            $table->text('sent_to'); //account na sinendan 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payment_transactions');
    }
};
