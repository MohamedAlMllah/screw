<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Game extends Model
{
    use HasFactory;

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
    public function hands()
    {
        return $this->hasMany(Hand::class);
    }
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function setHand(Card $card, $user_id)
    {
        $hand = new Hand();
        $hand->user_id = $user_id;
        $hand->game_id = $this->id;
        $hand->card_id = $card->id;
        $hand->save();
    }
    public function intialize()
    {
        $cards = Card::all();
        foreach ($cards as $card) {
            if ($card->value < 0) {
                $this->setHand($card, 1); //كومة مقلوبة
            } elseif ($card->value == 0) { //سكرو دريفر
                for ($i = 0; $i < 4; $i++) {
                    $this->setHand($card, 1);
                }
            } elseif ($card->value <= 10) {
                for ($i = 0; $i < 4; $i++) {
                    $this->setHand($card, 1);
                }
            } elseif ($card->value == 11) { //بصرة
                for ($i = 0; $i < 2; $i++) {
                    $this->setHand($card, 1);
                }
            } elseif ($card->value == 12) { //كعب داير
                for ($i = 0; $i < 2; $i++) {
                    $this->setHand($card, 1);
                }
            } elseif ($card->value == 13) { // هات وخد
                for ($i = 0; $i < 4; $i++) {
                    $this->setHand($card, 1);
                }
            } elseif ($card->value == 20) {
                for ($i = 0; $i < 4; $i++) {
                    $this->setHand($card, 1);
                }
            } elseif ($card->value == 25) { //سكرو احمر
                for ($i = 0; $i < 2; $i++) {
                    $this->setHand($card, 1);
                }
            }
        }
    }
    public function funat($user_id)
    {
        $komaMtfunata = Hand::where('game_id', $this->id)->where('user_id', $user_id)->inRandomOrder()->get();
        $cardsIds = $komaMtfunata->pluck('card_id');
        $koma = Hand::where('game_id', $this->id)->where('user_id', $user_id)->get();
        $index = 1;
        foreach ($koma as $key => $hand) {
            $hand->card_id = $cardsIds[$key];
            $hand->index = $index;
            $index++;
            $hand->save();
        }
    }
    public function wz3()
    {
        foreach ($this->participants as $participant) { // اللاعبين
            for ($i = 0; $i < 4; $i++) {
                $hand = Hand::where('game_id', $this->id)->where('user_id', 1)->first();
                $hand->user_id = $participant->user_id;
                $hand->index = $i + 1;
                $hand->save();
            }
        }
    }
    public function startRound(User $user)
    {
        foreach ($this->participants as $participant) {
            $participant->is_screw = 0;
            $participant->is_turn = false;
            $participant->skill = 'showTwoCards';
            $participant->save();
        }
        $user->participant->is_turn = true;
        $user->participant->save();
        $this->round++;
        $this->turns = 0;
        $this->save();
        $this->funat(1); //كومة مقلوبة
        $this->wz3();
        Announcement::where('game_id', $this->id)->delete();
    }

    public function getAwlElkomaElmkshofa()
    {
        $awlElkomaElmkshofa = Hand::where('game_id', $this->id)->where('user_id', 2)->orderBy('index', 'DESC')->first();
        if (!$awlElkomaElmkshofa) {
            $awlElkomaElmkshofa = $this->getAwlElkomaElmqlopa();
            $awlElkomaElmkshofa->user_id = 2;
            $awlElkomaElmkshofa->index = 1;
            $awlElkomaElmkshofa->save();
        }
        return $awlElkomaElmkshofa;
    }
    public function getAwlElkomaElmqlopa()
    {
        $awlElkomaElmqlopa = Hand::where('game_id', $this->id)->where('user_id', 1)->first();
        if (!$awlElkomaElmqlopa) {
            $this->endRound(); //تفنيطة واحدة
            $awlElkomaElmqlopa = Hand::where('game_id', $this->id)->where('user_id', 1)->first();
        }
        return $awlElkomaElmqlopa;
    }

    public function getPlayerInOrder($order, User $user)
    {
        $userParticipant = $this->participants->where('user_id', $user->id)->first();
        foreach ($this->participants as $key => $participant) {
            if ($userParticipant->id == $participant->id) {
                $userOrder = $key + 1;
            }
        }

        if ($userOrder + $order > $this->participants->count()) {
            $order = $userOrder + $order - $this->participants->count();
        } else {
            $order = $userOrder + $order;
        }

        foreach ($this->participants as $key => $participant) {
            if ($key == $order - 1) {
                return $participant->user;
            }
        }
        return null;
    }
    public function endTurn()
    {
        $currentPlayerTurn = Auth::user()->participant;
        //$currentPlayerTurn = Participant::where('game_id', $this->id)->where('is_turn', true)->first();
        $currentPlayerTurn->is_turn = false;
        $currentPlayerTurn->save();
        $nextPlayerTurn = $this->getPlayerInOrder(1, $currentPlayerTurn->user);
        if ($nextPlayerTurn->participant->is_screw) {
            $this->endRound();
        } else {
            $nextPlayerTurn->participant->is_turn = true;
            $nextPlayerTurn->participant->save();
            $this->turns++;
            $this->save();
        }
    }
    public function doubleScrewIfLosser(User $winnerPlayerInThisRound)
    {
        if (Participant::where('game_id', $this->id)->where('is_screw', 1)->first()) {
            $screwPlayer = Participant::where('game_id', $this->id)->where('is_screw', 1)->first()->user;
            if ($screwPlayer->id != $winnerPlayerInThisRound->id) {
                $screwPlayerScore = $screwPlayer->scores->where('round', $this->round)->first();
                $screwPlayerScore->value += $screwPlayer->calculateRoundScores();
                $screwPlayerScore->save();
            }
        }
    }

    public function playerWithLessScore()
    {
        $lessScoreInThisRound = 0;
        $winnerPlayerInThisRound = $this->admin;
        $winnerCardsCount = 0;
        foreach ($this->participants as $participant) {
            $playerRoundScore = $participant->user->calculateRoundScores();
            $participantCardsCount = Hand::where('user_id', $participant->user->id)->count();
            if ($participant->id == $this->admin->participant->id) {
                $lessScoreInThisRound = $playerRoundScore;
                $winnerPlayerInThisRound = $this->admin;
                $winnerCardsCount = $participantCardsCount;
            } elseif ($playerRoundScore < $lessScoreInThisRound || ($playerRoundScore = $lessScoreInThisRound && $participant->is_screw == 1) || ($playerRoundScore = $lessScoreInThisRound && $participantCardsCount < $winnerCardsCount && $winnerPlayerInThisRound->participant->is_screw != 1)) {
                $lessScoreInThisRound = $playerRoundScore;
                $winnerPlayerInThisRound = $participant->user;
            }
        }
        return User::find($winnerPlayerInThisRound->id);
    }
    public function endRound()
    {
        foreach ($this->participants as $participant) {
            $score = new Score();
            $score->value = $participant->user->calculateRoundScores();
            $score->round = $this->round;
            $score->user_id = $participant->user_id;
            $score->game_id = $this->id;
            $score->save();
        }
        $winnerPlayerInThisRound = $this->playerWithLessScore();
        foreach ($this->participants as $participant) {
            if ($participant->user->calculateRoundScores() == $winnerPlayerInThisRound->calculateRoundScores()) {
                $score = $participant->user->scores->where('round', $this->round)->first();
                $score->value -= $winnerPlayerInThisRound->calculateRoundScores();
                $score->save();
            }
        }
        $this->doubleScrewIfLosser($winnerPlayerInThisRound);
        $this->ermyAllCards();
        $this->startRound($this->playerWithLessScore());
    }
    public function screwPlayer()
    {
        $participant = $this->participants->where('is_screw')->first();
        if ($participant) {
            return $participant->user;
        }
        return null;
    }
    public function ermyAllCards()
    {
        foreach ($this->hands as $hand) {
            $hand->user_id = 1;
            $hand->save();
        }
    }

    public function gameIsFinished()
    {
        foreach ($this->participants as $participant) {;
            if ($participant->user->totalScore() >= $this->lose_score) {
                return true;
            }
        }
        return false;
    }
    public function canScrew(User $user)
    {
        if ($user->participant->is_turn && !$this->screwPlayer() && floor($this->turns  / $this->participants->count()) + 1 > 3) {
            return true;
        }
        return false;
    }
}
