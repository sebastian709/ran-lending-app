<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('referral_code', function (Blueprint $table) {
            $table->id();
            $table->string('referral_code', 255);
            $table->text('description')->nullable();
            $table->timestamp('availability')->nullable();
            $table->integer('created_by')->nullable();
            $table->unsignedBigInteger('loan_id_claimant')->nullable(); // loan_id foreign key
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_code');
    }
};

