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
        Schema::create('loan_appeal', function (Blueprint $table) {
            $table->id(); // auto-increment primary key
            $table->unsignedBigInteger('loan_id'); // reference to loan_application
            $table->unsignedBigInteger('payment_id'); // reference to transaction id
            $table->text('reason'); // appeal reason
            $table->string('uploaded_proof')->nullable(); // file path or URL
            $table->timestamp('date_of_appeal')->useCurrent(); // default now
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_appeal');
    }
};
