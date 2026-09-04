<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tujuan_tabungans', function (Blueprint $table) {
            $table->id('id_tujuan');

            $table->unsignedBigInteger('id_user');

            $table->string('nama_tujuan');

            $table->decimal('target_jumlah', 15, 2);

            $table->decimal('jumlah_terkumpul', 15, 2)
                ->default(0);

            $table->date('deadline')->nullable();

            $table->text('deskripsi')->nullable();

            $table->timestamps();

            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tujuan_tabungans');
    }
};