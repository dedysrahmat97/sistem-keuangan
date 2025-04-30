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
        Schema::create('jurnal_umum_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurnal_umum_id')->constrained('jurnal_umum')->onDelete('cascade');
            $table->foreignId('akun_id')->constrained('akun')->onDelete('cascade');
            $table->enum('tipe', ['debet', 'kredit']);
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_umum_detail');
    }
};
