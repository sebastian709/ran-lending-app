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
        Schema::create('loan_tenure_penalty', function (Blueprint $table) {
            $table->id();
            $table->integer('tenure_id')->index();
            $table->float('penalty');
            $table->integer('payment_status_id')->default(value: '1')->index();
            $table->integer('payment_id')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_tenure_penalty');
    }
};
