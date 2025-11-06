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
        Schema::create('artikel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('repositori_id')->nullable();
            $table->text('judul');
            $table->text('slug')->unique();
            $table->string('cover',100)->nullable();
            $table->longtext('isi');
            $table->string('file', 100)->nullable();
            $table->enum('status',['publik','private']);
            $table->integer('views')->nullable(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
