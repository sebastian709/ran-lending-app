<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\loan\loan_payment_account;

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
            $table->text('account_name');
            $table->text('image');
            $table->integer('status')->index();
            $table->timestamps();
        });

        loan_payment_account::insert([
            [
                'account_number' => '8279258976',
                'account_name' => 'Nida C. Lingat',
                'image' => '/images/bpi.jpg',
                'status' => '1',
            ]
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
