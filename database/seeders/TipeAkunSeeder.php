<?php

namespace Database\Seeders;

use App\Models\TipeAkun;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipeAkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipeAkuns = [
            ['nama_tipe' => 'Aset'],
            ['nama_tipe' => 'Liabilitas'],
            ['nama_tipe' => 'Ekuitas'],
            ['nama_tipe' => 'Pendapatan'],
            ['nama_tipe' => 'Beban'],
        ];

        DB::beginTransaction();
        try {

            foreach ($tipeAkuns as $index => $tipe) {
                $id = TipeAkun::create([
                    'nama_tipe' => $tipe['nama_tipe'],
                    'kode_tipe' => (++$index),
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            // throw $th;
            echo 'Error seeding users: '.$th->getMessage();

        }
    }
}
