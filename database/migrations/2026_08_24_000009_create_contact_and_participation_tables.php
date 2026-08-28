<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Pesan Masuk dari Kontak Kami
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable(); // misal: kemitraan, informasi_program, permintaan_data, undangan, lainnya
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 2. Tabel Formulir Pendaftaran / Minat Keterlibatan dari Ikut Serta
        Schema::create('participations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('interest'); // misal: relawan, riset_magang, kemitraan_cso, kolaborasi_media, lainnya
            $table->text('message')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participations');
        Schema::dropIfExists('contact_messages');
    }
};
