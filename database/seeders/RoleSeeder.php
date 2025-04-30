<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $admin = Role::create([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $user = User::where('email', 'admin@tes.com')->first();
            $user->assignRole($admin);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception if needed
            echo 'Error seeding users: '.$e->getMessage();
        }
    }
}