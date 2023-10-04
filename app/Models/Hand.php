<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hand extends Model
{
    use HasFactory;


    public function card()
    {
        return $this->belongsTo(Card::class);
    }
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
