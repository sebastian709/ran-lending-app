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
        Schema::create('admin_money_transfer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');            // related loan
            $table->unsignedBigInteger('processed_by');           // who processed (auth user)
            $table->string('reference_number')->unique();     // transaction ref no.
            $table->string('proof_of_transfer')->nullable();    // uploaded screenshot path
            $table->date('transfer_date');                    // actual transfer date
            // $table->date('monthly_due_date')->nullable();     // due date (auto or custom)
            $table->text('remarks')->nullable();              // optional remarks
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_money_transfer');
    }
};
