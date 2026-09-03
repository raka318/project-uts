<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');

            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_dompet');
            $table->unsignedBigInteger('id_kategori');

            $table->enum('jenis', [
                'pemasukan',
                'pengeluaran'
            ]);

            $table->decimal('jumlah', 15, 2);

            $table->string('judul');

            $table->text('deskripsi')->nullable();

            $table->date('tanggal');

            $table->timestamps();

            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_dompet')
                ->references('id_dompet')
                ->on('dompets')
                ->onDelete('cascade');

            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategoris')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};