<?php

namespace Database\Seeders;

use App\Models\ConnectionStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConnectionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $boxSizes = [
            ['id' => 1, 'name' => 'New Sent/Received Request'],
            ['id' => 2, 'name' => 'Connected'],
        ];

        foreach ($boxSizes as $boxSize) {
            ConnectionStatus::create($boxSize);
        }
    }
}
