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
        Schema::create('loan_payment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->timestamps();
        });
        
        DB::table('loan_payment_statuses')->insert([
            ['type' => 'For Verification'],
            ['type' => 'For Correction'],
            ['type' => 'Verified'],
        ]);

    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payment_statuses');
    }
};
