<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $DemoUsers = [
            ['name' => "Jawad Ahmad",
                'email' => "test@gmail.com",
                'email_verified_at' => now(),
                'password' => '$2y$10$ZC9gpEe28FUV5SuZSlHtk.gqAp4j61VKBMqyhGAeusKW522/rFMGa',
                'remember_token' => Str::random(10)],
        ];

        foreach ($DemoUsers as $DemoUser) {
            User::create($DemoUser);
        }
    }
}
