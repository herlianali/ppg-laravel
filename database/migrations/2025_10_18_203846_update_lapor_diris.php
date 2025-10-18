<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('verifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapor_diri_id')->constrained('lapor_diris')->onDelete('cascade');
            $table->enum('status', ['diproses', 'diterima', 'ditolak', 'revisi'])->default('diproses');
            $table->text('komentar')->nullable();
            $table->string('verifikator')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('verifikasi');
    }
};
