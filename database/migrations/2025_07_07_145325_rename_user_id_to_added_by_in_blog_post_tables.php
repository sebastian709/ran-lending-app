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
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->renameColumn('user_id', 'added_by');
            $table->unsignedBigInteger('updated_by')->after('added_by');
            $table->unsignedBigInteger('deleted_by')->after('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('updated_by');
            $table->dropColumn('deleted_by');
            $table->renameColumn('added_by', 'user_id');
        });
    }
};
