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
            $table->text('remarks')->nullable()->after('billing_statement_img'); 
            // replace 'some_existing_column' with the column you want it after
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_rejected_fields', function (Blueprint $table) {
            //
        });
    }
};
