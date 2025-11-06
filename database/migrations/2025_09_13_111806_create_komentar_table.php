<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. komentar
        Schema::create('komentar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artikel_id')->constrained('artikel')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('komentar')->onDelete('cascade');
            $table->text('isi');
            $table->timestamps();
        });

        // 2. komentar_vote
        Schema::create('komentar_vote', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komentar_id')->constrained('komentar')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('vote_type', ['like', 'dislike']);
            $table->timestamps();

            $table->unique(['komentar_id', 'user_id']); // 1 user hanya bisa vote 1x per komentar
        });

        // 4. komentar_tag
        Schema::create('komentar_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komentar_id')->constrained('komentar')->onDelete('cascade');
            $table->foreignId('tagged_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 5. notifikasi
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // penerima notif
            $table->enum('tipe', ['komentar', 'balasan', 'tag', 'like']);
            $table->unsignedBigInteger('referensi_id'); // id komentar/artikel terkait
            $table->string('pesan', 255);
            $table->enum('status', ['belum_dibaca', 'dibaca'])->default('belum_dibaca');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('komentar_tag');
        Schema::dropIfExists('komentar_file');
        Schema::dropIfExists('komentar_vote');
        Schema::dropIfExists('komentar');
    }
};
