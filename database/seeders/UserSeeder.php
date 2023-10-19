<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'KomaMqloba',
            'email' => 'koma_mqloba@gmail.com',
            'password' => bcrypt('abcd@1234'),
        ]);
        User::create([
            'name' => 'KomaMkshofa',
            'email' => 'koma_mkshofa@gmail.com',
            'password' => bcrypt('abcd@1234'),
        ]);
        User::create([
            'name' => 'Turbo',
            'email' => 'player1@gmail.com',
            'password' => bcrypt('0000000000'),
        ]);
        User::create([
            'name' => 'Kimo',
            'email' => 'player2@gmail.com',
            'password' => bcrypt('0000000000'),
        ]);
        User::create([
            'name' => 'Abu Ali',
            'email' => 'player3@gmail.com',
            'password' => bcrypt('0000000000'),
        ]);
        User::create([
            'name' => 'Bido',
            'email' => 'player4@gmail.com',
            'password' => bcrypt('0000000000'),
        ]);
    }
}
