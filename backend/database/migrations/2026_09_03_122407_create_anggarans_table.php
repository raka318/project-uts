<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggarans', function (Blueprint $table) {
            $table->id('id_anggaran');

            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_kategori');

            $table->string('nama_anggaran');

            $table->decimal('jumlah', 15, 2);

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->timestamps();

            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategoris')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
};