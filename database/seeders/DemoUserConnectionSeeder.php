<?php

namespace Database\Seeders;

use App\Models\Connection;
use App\Models\User;
use database\factories\DemoUserConnectionFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoUserConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($x = 0; $x <= 50; $x++) {
            $DemoUser = [
                'requester_user_id' => rand(2, User::count()),
                'recipient_user_id' => 1,
                'status' => rand(1, 2)
            ];
            Connection::create($DemoUser);
        }

    }
}
