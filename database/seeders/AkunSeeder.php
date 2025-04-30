<?php

namespace Database\Seeders;

use App\Models\Akun;
use App\Models\TipeAkun;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AkunSeeder extends Seeder
{
    public function run(): void
    {
        $namaAkunByTipe = [
            'Aset' => ['Rumah', 'Mobil', 'Tanah', 'Peralatan'],
            'Liabilitas' => ['Utang Bank', 'Utang Dagang', 'Utang Pajak', 'Utang Lain-lain'],
            'Ekuitas' => ['Modal Pemilik', 'Prive', 'Laba Ditahan', 'Penambahan Modal'],
            'Pendapatan' => ['Penjualan', 'Pendapatan Jasa', 'Pendapatan Sewa', 'Pendapatan Bunga'],
            'Beban' => ['Beban Listrik', 'Beban Gaji', 'Beban Sewa', 'Beban Penyusutan'],
        ];

        $tipeAkuns = TipeAkun::all();

        DB::beginTransaction();
        try {
            foreach ($tipeAkuns as $tipe) {
                $namaAkuns = $namaAkunByTipe[$tipe->nama_tipe] ?? ['Akun 1', 'Akun 2', 'Akun 3', 'Akun 4'];

                foreach ($namaAkuns as $i => $nama) {
                    $urutan = $i + 1; // dimulai dari 1
                    $kodeAkun = $tipe->id.$urutan;

                    Akun::create([
                        'tipe_akun_id' => $tipe->id,
                        'kode_akun' => $kodeAkun,
                        'nama_akun' => $tipe->nama_tipe.' '.$nama,
                        'pos_saldo' => $i % 2 == 0 ? 'debet' : 'kredit',
                        'pos_laporan' => in_array($tipe->nama_tipe, ['Pendapatan', 'Beban']) ? 'laba_rugi' : 'neraca',
                        'saldo_awal' => rand(100000, 500000),
                    ]);
                }
            }

            DB::commit();
            echo "Seeding akun berhasil.\n";
        } catch (\Throwable $th) {
            DB::rollBack();
            echo 'Error seeding akun: '.$th->getMessage()."\n";
        }
    }
}
