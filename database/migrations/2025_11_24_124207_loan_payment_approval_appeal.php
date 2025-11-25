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
        Schema::create('loan_payment_approval_appeal', function (Blueprint $table) {
            $table->id(); // auto-increment primary key
            $table->integer('loan_payment_approval_log_id'); // reference to loan_application
            $table->text('reason'); // appeal reason
            $table->string('attachment'); // file path or URL
            $table->boolean('status')->default(1);
            $table->boolean('admin_verdict')->nullable();
            $table->boolean('admin_verdict_date')->nullable();
            $table->boolean('admin_verdict_by')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
                Schema::dropIfExists('loan_payment_approval_appeal');

    }
};
