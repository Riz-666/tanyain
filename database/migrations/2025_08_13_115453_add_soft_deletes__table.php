<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->softDeletes(); // untuk kolom deleted_at
            $table->dateTime('deleted_until')->nullable(); // batas restore
        });

        Schema::table('repositori', function (Blueprint $table) {
            $table->softDeletes();
            $table->timestamp('deleted_until')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes(); // untuk kolom deleted_at
            $table->dateTime('deleted_until')->nullable(); // batas restore
        });
        Schema::table('file_repo', function (Blueprint $table) {
            $table->softDeletes(); // untuk kolom deleted_at
            $table->dateTime('deleted_until')->nullable(); // batas restore
        });

    }

    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deleted_until');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deleted_until');
        });
        Schema::table('file_repo', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deleted_until');
        });

        Schema::table('repositori', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deleted_until');
        });
    }
};
