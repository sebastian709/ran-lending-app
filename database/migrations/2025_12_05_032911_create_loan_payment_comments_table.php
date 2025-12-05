<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_payment_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_id')->length(11);
            $table->integer('tenure_id')->length(11);
            $table->text('comment');
            $table->integer('comment_by')->length(11); 
            $table->tinyInteger('status')->default(1);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_payment_comments');
    }
};
