<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    protected $hidden = ['user'];

    public function endSkill()
    {
        if ($this->skill != 'showTwoCards') {
            $this->game->endTurn();
        }
        $this->skill = 'normal';
        $this->save();
    }
}
