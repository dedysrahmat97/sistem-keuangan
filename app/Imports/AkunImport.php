<?php

namespace App\Imports;

use App\Models\Akun; // Pastikan model Akun diimpor
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AkunImport implements ToCollection, WithHeadingRow
{
    /**
     * Handle collection of rows
     */
    public function collection(Collection $collection)
    {
        $data = [];
        $i = 1; // Inisialisasi variabel $i

        foreach ($collection as $row) {

            // Bangun array data untuk dimasukkan ke database
            $data[] = [
                'tipe_akun_id' => $row['tipe_akun_id'] ?? null, // Kolom sesuai header file Excel
                'kode_akun' => $row['kode_akun'] ?: null,
                'nama_akun' => $row['nama_akun'] ?? null,
                'pos_saldo' => $row['pos_saldo'] ?? null,
                'pos_laporan' => $row['pos_laporan'] ?? null,
                'saldo_awal' => $row['saldo_awal'] ?? 0,
                'created_at' => now(), // Tambahkan timestamp
                'updated_at' => now(), // Tambahkan timestamp
            ];
        }

        // Simpan data ke tabel Akun
        Akun::insert($data);
    }
}
