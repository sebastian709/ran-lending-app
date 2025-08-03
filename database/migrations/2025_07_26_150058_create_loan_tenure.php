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
        Schema::create('loan_tenure', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_id')->index();
            $table->integer('count')->index();
            $table->dateTime('date');
            $table->float('principal');
            $table->integer('payment_status_id')->default('1')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_tenure');
    }
};
