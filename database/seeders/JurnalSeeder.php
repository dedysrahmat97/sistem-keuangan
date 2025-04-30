<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JurnalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // Fetch all IDs of Akun
            $akunIds = DB::table('akun')->pluck('id')->toArray();

            // Generate 35 journal entries
            for ($i = 1; $i <= 35; $i++) {
                // Create a Jurnal Umum
                $jurnalUmumId = DB::table('jurnal_umum')->insertGetId([
                    'tanggal' => now()->subDays(rand(0, 60)), // Random date within the last 60 days
                    'keterangan' => 'Transaksi #'.$i,
                    'bukti_transfer' => Str::uuid(),
                ]);

                // Randomly pick akun IDs for debet and kredit
                $debetAkunId = $akunIds[array_rand($akunIds)];
                $kreditAkunId = $akunIds[array_rand($akunIds)];

                // Ensure debet and kredit akun are not the same
                while ($debetAkunId === $kreditAkunId) {
                    $kreditAkunId = $akunIds[array_rand($akunIds)];
                }

                // Random nominal value
                $nominal = rand(100000, 1000000); // Random value between 100,000 and 1,000,000

                // Create Jurnal Umum Detail for Debet
                DB::table('jurnal_umum_detail')->insert([
                    'jurnal_umum_id' => $jurnalUmumId,
                    'akun_id' => $debetAkunId,
                    'tipe' => 'debet',
                    'nominal' => $nominal,
                ]);

                // Create Jurnal Umum Detail for Kredit
                DB::table('jurnal_umum_detail')->insert([
                    'jurnal_umum_id' => $jurnalUmumId,
                    'akun_id' => $kreditAkunId,
                    'tipe' => 'kredit',
                    'nominal' => $nominal,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception if needed
            echo 'Error seeding users: '.$e->getMessage();
        }
    }
}
