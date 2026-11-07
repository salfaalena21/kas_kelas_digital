<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DeploySeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class,
            IuranSeeder::class,
        ]);
    }
}
