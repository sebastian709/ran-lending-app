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
        Schema::create('admin_loan_request_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->integer('pending')->default(0)->index();
            $table->integer('for_interview')->default(0)->index();
            $table->integer('for_revision')->default(0)->index();
            $table->integer('waiting')->default(0)->index();
            $table->integer('status')->default(1)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_loan_request_access');
    }
};
