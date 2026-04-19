<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Super Admin – Akses ke Filament Panel
        User::updateOrCreate(
            ['email' => 'admin@cliphub.com'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('password'),
                'role'     => 'admin',
            ]
        );

        // Kreator / Clipper Demo Account
        User::updateOrCreate(
            ['email' => 'kreator@cliphub.com'],
            [
                'name'     => 'Tio',
                'password' => bcrypt('password'),
                'role'     => 'kreator',
            ]
        );

        // Brand / Agensi Demo Account
        User::updateOrCreate(
            ['email' => 'brand@cliphub.com'],
            [
                'name'     => 'Skincare Brand',
                'password' => bcrypt('password'),
                'role'     => 'brand',
                'balance'  => 10000000
            ]
        );

        $this->call([
            CampaignSeeder::class,
        ]);
    }
}
