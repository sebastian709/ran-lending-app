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
        Schema::table('loan_appeal', function (Blueprint $table) {
            $table->tinyInteger('receive_status')
              ->default(0)
              ->after('uploaded_proof'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_appeal', function (Blueprint $table) {
            //
        });
    }
};
