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
        Schema::create('dompets', function (Blueprint $table) {
            $table->id('id_dompet');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('nama_dompet');
            $table->enum('jenis', ['cash', 'bank', 'ewallet', 'tabungan']);
            $table->decimal('saldo', 15, 2)->default(0);
            $table->string('mata_uang')->default('IDR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dompets');
    }
};