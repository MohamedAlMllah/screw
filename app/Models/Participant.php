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
        $skill = $this->skill;
        $this->skill = 'normal';
        $this->save();
        if ($skill != 'showTwoCards') {
            $this->game->endTurn();
        }
    }
}
