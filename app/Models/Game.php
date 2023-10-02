<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function startRound()
    {
        $this->cycle = 1; // not used yet
        $this->round += 1;
        $this->save();
        $this->funat(1); //كومة مقلوبة
        $this->wz3();
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
    }
    public function endTurn()
    {
        $currentPlayerTurn = Participant::where('game_id', $this->id)->where('is_turn', true)->first();
        $currentPlayerTurn->is_turn = false;
        $currentPlayerTurn->save();
        $nextPlayerTurn = $this->getPlayerInOrder(1, $currentPlayerTurn->user);
        if ($nextPlayerTurn->participant->is_screw) {
            $this->endRound();
        } else {
            $nextPlayerTurn->participant->is_turn = true;
            $nextPlayerTurn->participant->save();
        }
    }
    public function doubleScrewIfLosser(User $winnerPlayerInThisRound)
    {
        if (Participant::where('game_id', $this->id)->where('is_screw', true)->first()) {
            $screwPlayer = Participant::where('game_id', $this->id)->where('is_screw', true)->first()->user;
            if ($screwPlayer->id != $winnerPlayerInThisRound->id) {
                $screwPlayerScore = $screwPlayer->scores->where('round', $this->round)->first();
                $screwPlayerScore->value += $screwPlayer->calculateRoundScores();
                $screwPlayerScore->save();
            }
        }
    }

    public function playerWithLessScore()
    {
        /* $screwPlayer = Score::where('game_id', $this->id)->where('is_screw', true)->first();
        if ($screwPlayer && $screwPlayer->user->calculateRoundScores() == 0) {
            return User::find($screwPlayer->user_id);
        }*/ //not tested 
        $lessScoreInThisRound = 0;
        foreach ($this->participants as $participant) {
            $playerRoundScore = $participant->user->calculateRoundScores();
            if ($participant->id == $this->admin->participant->id) {
                $lessScoreInThisRound = $playerRoundScore;
                $winnerPlayerInThisRound = $this->admin;
            } elseif ($playerRoundScore < $lessScoreInThisRound || ($playerRoundScore == $lessScoreInThisRound && $participant->is_screw)) {
                $lessScoreInThisRound = $playerRoundScore;
                $winnerPlayerInThisRound = $participant->user;
            }
        }
        return User::find($winnerPlayerInThisRound->id);
    }
    public function endRound()
    {
        // حد خلص كل كروته كمل اللفة واقفل كأنه سكرو
        //خلصنا عدد التفنيطات المتاحة للراوند

        foreach ($this->participants as $participant) {
            $score = new Score();
            $score->value = $participant->user->calculateRoundScores();
            $score->round = $this->round;
            $score->user_id = $participant->user_id;
            $score->save();
        }
        $winnerPlayerInThisRound = $this->playerWithLessScore();
        $winnerScore = $winnerPlayerInThisRound->scores->where('round', $this->round)->first();
        $winnerScore->value -= $winnerPlayerInThisRound->calculateRoundScores();
        $winnerScore->save();
        $this->doubleScrewIfLosser($winnerPlayerInThisRound);
        $this->ermyAllCards();
        $this->startRound();
        return $winnerPlayerInThisRound;
    }
    public function hasScrewPlayer()
    {
        return $this->participants->where('is_screw', true)->first();
    }
    public function ermyAllCards()
    {
        $winner = $this->playerWithLessScore();
        foreach ($this->hands as $hand) {
            $hand->user_id = 1;
            $hand->save();
        }
        foreach ($this->participants as $participant) {
            $participant->is_screw = false;
            $participant->is_turn = false;
            $participant->save();
        }
        $winner->participant->is_turn = true;
        $winner->participant->save();
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
}
