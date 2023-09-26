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
        Card::create(['value' => -1 , 'image' => 'images/cards/negativeOne.png']);
        
        Card::create(['value' => 0 , 'image' => 'images/cards/screwDriver.png']);

        Card::create(['value' => 1 , 'image' => 'images/cards/1.png']);

        Card::create(['value' => 2 , 'image' => 'images/cards/2.png']);

        Card::create(['value' => 3 , 'image' => 'images/cards/3.png']);

        Card::create(['value' => 4 , 'image' => 'images/cards/4.png']);

        Card::create(['value' => 5 , 'image' => 'images/cards/5.png']);

        Card::create(['value' => 6 , 'image' => 'images/cards/6.png']);

        Card::create(['value' => 7 , 'image' => 'images/cards/7.png']);

        Card::create(['value' => 8 , 'image' => 'images/cards/8.png']);

        Card::create(['value' => 9 , 'image' => 'images/cards/9.png']);

        Card::create(['value' => 10 , 'image' => 'images/cards/10.png']);

        Card::create(['value' => 11 , 'image' => 'images/cards/bsra.png']);//بصرة
        
        Card::create(['value' => 12 , 'image' => 'images/cards/kaabDayer.png']);//كعب داير
        
        Card::create(['value' => 13 , 'image' => 'images/cards/khodWHat.png']);//خد وهات

        Card::create(['value' => 20 , 'image' => 'images/cards/plusTwenty.png']);

        Card::create(['value' => 25 , 'image' => 'images/cards/screw.png']);

    }
}
