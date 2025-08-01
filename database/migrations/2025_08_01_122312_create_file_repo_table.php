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
        Schema::create('file_repo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repositori_id');
            $table->string('nama_file');
            $table->string('path');
            $table->string('ekstensi')->nullable();
            $table->integer('ukuran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_repo');
    }
};
