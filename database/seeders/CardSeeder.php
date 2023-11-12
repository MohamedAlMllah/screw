<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Card::create(['name' => '-1', 'value' => -1, 'image' => 'images/cards/negativeOne.png']);

        Card::create(['name' => 'screwdriver', 'value' => 0, 'image' => 'images/cards/screwDriver.png']);

        Card::create(['name' => '1', 'value' => 1, 'image' => 'images/cards/1.png']);

        Card::create(['name' => '2', 'value' => 2, 'image' => 'images/cards/2.png']);

        Card::create(['name' => '3', 'value' => 3, 'image' => 'images/cards/3.png']);

        Card::create(['name' => '4', 'value' => 4, 'image' => 'images/cards/4.png']);

        Card::create(['name' => '5', 'value' => 5, 'image' => 'images/cards/5.png']);

        Card::create(['name' => '6', 'value' => 6, 'image' => 'images/cards/6.png']);

        Card::create(['name' => '7', 'value' => 7, 'image' => 'images/cards/7.png']);

        Card::create(['name' => '8', 'value' => 8, 'image' => 'images/cards/8.png']);

        Card::create(['name' => '9', 'value' => 9, 'image' => 'images/cards/9.png']);

        Card::create(['name' => '10', 'value' => 10, 'image' => 'images/cards/10.png']);

        Card::create(['name' => 'bsra', 'value' => 11, 'image' => 'images/cards/bsra.png']); //بصرة

        Card::create(['name' => 'k3b dayeer', 'value' => 12, 'image' => 'images/cards/kaabDayer.png']); //كعب داير

        Card::create(['name' => '5od w hat', 'value' => 13, 'image' => 'images/cards/khodWHat.png']); //خد وهات

        Card::create(['name' => '+20', 'value' => 20, 'image' => 'images/cards/plusTwenty.png']);

        Card::create(['name' => 'red screw', 'value' => 25, 'image' => 'images/cards/screw.png']);
    }
}
