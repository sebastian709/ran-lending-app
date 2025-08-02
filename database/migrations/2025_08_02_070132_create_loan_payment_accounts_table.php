<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\loan\loan_tenure;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->text('account_number')->index();
            $table->integer('account_name');
            $table->text('image');
            $table->integer('status','1')->index();
            $table->timestamps();
        });

        loan_tenure::create([
            'account_number' => '8279258976',
            'account_name' => 'Nida C. Lingat',
            'image' => '/images/bpi.jpg',
        ]);
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payment_accounts');
    }
};
