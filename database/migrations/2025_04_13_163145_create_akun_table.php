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
        Schema::create('akun', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipe_akun_id')->references('id')->on('tipe_akun')->constrained()->onDelete('cascade');
            $table->string('kode_akun')->unique();
            $table->string('nama_akun');
            $table->enum('pos_saldo', ['kredit', 'debet']);
            $table->enum('pos_laporan', ['laba_rugi', 'neraca']);
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->decimal('saldo_akhir', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun');
    }
};
