<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@tes.com',
                'password' => Hash::make('1234'),
            ]);
            User::create([
                'name' => 'Ketua',
                'email' => 'ketua@tes.com',
                'password' => Hash::make('1234'),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception if needed
            echo 'Error seeding users: '.$e->getMessage();
        }
    }
}