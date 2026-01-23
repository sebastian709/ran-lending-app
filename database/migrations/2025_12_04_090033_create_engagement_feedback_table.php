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
        Schema::create('engagement_feedback', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('loan_id')->index(); // Assuming it links to loans table
            $table->string('referral')->nullable();
            $table->string('social_media')->nullable();
            $table->timestamp('created_at')->useCurrent(); 
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engagement_feedback');
    }
};
