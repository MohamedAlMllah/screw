<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bootstrap\HandleExceptions;

class Game extends Model
{
    use HasFactory;

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function scores()
    {
        return $this->hasMany(Score::class);
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
        $komaMtfunata = Hand::where('user_id', $user_id)->inRandomOrder()->get();
        $cardsIds = $komaMtfunata->pluck('card_id');
        $koma = Hand::where('user_id', $user_id)->get();
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
        foreach ($this->scores as $score) { // اللاعبين
            for ($i = 0; $i < 4; $i++) {
                $hand = Hand::where('game_id', $this->id)->where('user_id', 1)->first();
                $hand->user_id = $score->user->id;
                $hand->index = $i + 1;
                $hand->save();
            }
        }
    }
    public function startRound()
    {
        $this->cycle = 1; // not used yet
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
        $userScore = $this->scores->where('user_id', $user->id)->first();
        foreach ($this->scores as $key => $score) {
            if ($userScore->id == $score->id) {
                $userOrder = $key + 1;
            }
        }

        if ($userOrder + $order > $this->scores->count()) {
            $order = $userOrder + $order - $this->scores->count();
        } else {
            $order = $userOrder + $order;
        }

        foreach ($this->scores as $key => $score) {
            if ($key == $order - 1) {
                return $score->user;
            }
        }
    }
    public function endTurn()
    {
        $currentPlayerTurn = Score::where('game_id', $this->id)->where('turn', true)->first();
        $currentPlayerTurn->turn = false;
        $currentPlayerTurn->save();
        $nextPlayerTurn = $this->getPlayerInOrder(1, $currentPlayerTurn->user);
        if ($nextPlayerTurn->score->screw) {
            $this->endRound();
        } else {
            $nextPlayerTurn->score->turn = true;
            $nextPlayerTurn->score->save();
        }
    }
    public function doubleScrewIfLosser(User $winnerPlayerInThisRound)
    {
        if (Score::where('game_id', $this->id)->where('screw', true)->first()) {
            $screwPlayer = Score::where('game_id', $this->id)->where('screw', true)->first()->user;
            if ($screwPlayer->id != $winnerPlayerInThisRound->id) {
                $screwPlayer->score->score += $screwPlayer->calculateRoundScores();
                $screwPlayer->score->save();
            }
        }
    }

    public function playerWithLessScore()
    {
        /* $screwPlayer = Score::where('game_id', $this->id)->where('screw', true)->first();
        if ($screwPlayer && $screwPlayer->user->calculateRoundScores() == 0) {
            return User::find($screwPlayer->user_id);
        }*/ //not tested 
        $lessScoreInThisRound = 0;
        foreach ($this->scores as $score) {
            $playerRoundScore = $score->user->calculateRoundScores();
            if ($score->id == $this->admin->score->id) {
                $lessScoreInThisRound = $playerRoundScore;
                $winnerPlayerInThisRound = $this->admin;
            } elseif ($playerRoundScore < $lessScoreInThisRound || ($playerRoundScore == $lessScoreInThisRound && $score->screw)) {
                $lessScoreInThisRound = $playerRoundScore;
                $winnerPlayerInThisRound = $score->user;
            }
        }
        return User::find($winnerPlayerInThisRound->id);
    }
    public function endRound()
    {
        // حد خلص كل كروته كمل اللفة واقفل كأنه سكرو
        //خلصنا عدد التفنيطات المتاحة للراوند

        foreach ($this->scores as $score) {
            $score->score += $score->user->calculateRoundScores();
            $score->save();
        }
        $winnerPlayerInThisRound = $this->playerWithLessScore();
        $winnerPlayerInThisRound->score->score -= $winnerPlayerInThisRound->calculateRoundScores();
        $winnerPlayerInThisRound->score->save();
        $this->doubleScrewIfLosser($winnerPlayerInThisRound);
        $this->ermyAllCards();
        $this->startRound();
        return $winnerPlayerInThisRound;
    }
    public function hasScrewPlayer()
    {
        return $this->scores->where('screw', true)->first();
    }
    public function ermyAllCards()
    {
        $winner = $this->playerWithLessScore();
        foreach ($this->hands as $hand) {
            $hand->user_id = 1;
            $hand->save();
        }
        foreach ($this->scores as $score) {
            $score->screw = false;
            $score->turn = false;
            $score->save();
        }
        $winner->score->turn = true;
        $winner->score->save();
    }

    public function gameIsFinished()
    {
        foreach ($this->scores as $score) {
            if ($score->score >= $this->lose_score) {
                return true;
            }
        }
        return false;
    }
}
