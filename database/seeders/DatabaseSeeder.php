<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DemoUserSeeder::class,
            UserSeeder::class,
            ConnectionStatusSeeder::class,
            ConnectionSeeder::class,
            DemoUserConnectionSeeder::class,
        ]);

    }
}
