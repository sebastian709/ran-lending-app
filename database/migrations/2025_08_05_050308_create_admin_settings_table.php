<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('value')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });


        // Insert default data right after table creation
        DB::table('admin_settings')->insert([
            ['title' => 'loan_interest', 'value' => '5', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_settings');
    }
};
