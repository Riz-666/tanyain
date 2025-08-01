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
        Schema::table('artikel', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('artikel', function (Blueprint $table) {
            $table->foreign('repositori_id')->references('id')->on('repositori')->nullOnDelete();
        });

        Schema::table('repositori', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('file_repo', function (Blueprint $table) {
            $table->foreign('repositori_id')->references('id')->on('repositori')->onDelete('cascade');
        });

        Schema::table('view_artikel', function (Blueprint $table) {
            $table->foreign('artikel_id')->references('id')->on('artikel')->onDelete('cascade');
        });

        schema::table('view_artikel', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('saran', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('repositori', function (Blueprint $table) {
            $table->dropForeign(['artikel_id']);
        });

        Schema::table('view_artikel', function (Blueprint $table) {
            $table->dropForeign(['artikel_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('saran', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('file_repositori', function (Blueprint $table) {
            $table->dropForeign(['repositori_id']);
        });
    }
};
