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
        Schema::create('loan_payment_approval_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_payment_id')->index(); 
            $table->integer('action')->index();  //loan_payment_statuses table
            $table->text('attachment')->nullable();
            $table->text('reason')->nullable();
            $table->text('remarks')->nullable();
            $table->text('instruction')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('added_by')->index(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payment_approval_logs');
    }
};
