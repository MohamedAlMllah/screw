<?php

namespace App\Http\Controllers;

use App\Models\Hand;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;

class HandController extends Controller
{
    public function ekshif(Hand $hand)
    {
        if ($hand->user->participant && $hand->user->participant->skill == 'showTwoCards') {
            $hand1 = $hand->user->hands->where('index', 1)->first();
            $hand2 = $hand->user->hands->where('index', 2)->first();
            $skill = $hand->user->participant->skill;
        } elseif ($hand->user->participant && $hand->user->participant->skill == 'bossFeWrqtak') {
            $skill = $hand->user->participant->skill;
        } elseif (Auth::user()->participant && Auth::user()->participant->skill == 'bossFeWrqtGherak') {
            $skill = Auth::user()->participant->skill;
        }
        return View('cards.kshf', [
            'hand' => $hand,
            'hand1' => $hand1 ?? null,
            'hand2' => $hand2 ?? null,
            'skill' => $skill ?? 'normal',
            'user' => Auth::user()
        ]);
    }
    public function ermy(Hand $hand)
    {
        $cardId = $hand->card_id;
        $userId = $hand->user_id;
        $hand->user_id = 2;
        $hand->index = $hand->game->getAwlElkomaElmkshofa()->index + 1;
        $hand->save();
        $participant = Participant::where('user_id', Auth::user()->id)->first();
        if ($userId == 1 && $cardId >= 9 && $cardId <= 10) {
            $participant->skill = 'bossFeWrqtak';
            $participant->save();
        } elseif ($userId == 1 && $cardId >= 11 && $cardId <= 12) {
            $participant->skill = 'bossFeWrqtGherak';
            $participant->save();
        } elseif ($userId == 1 && $cardId == 13) { //bsra
            $participant->skill = 'bsra';
            $participant->save();
        } elseif ($userId == 1 && $cardId == 15) { //hat w5od
            $participant->skill = 'KhodWHat';
            $participant->save();
        } else {
            Auth::user()->participant->endSkill();
        }
        return redirect()->route('home');
    }
    public function bsra(Hand $hand)
    {
        $awlElkomaElmkshofa = $hand->game->getAwlElkomaElmkshofa();
        if ($hand->card->id == $awlElkomaElmkshofa->card->id || abs($hand->card->value - $awlElkomaElmkshofa->card->value) == 25 || $hand->user->participant->skill == 'bsra') {
            $myHands = Hand::where('user_id', $hand->user_id)->where('index', '>', $hand->index)->get();
            foreach ($myHands as $myHand) {
                $myHand->index--;
                $myHand->save();
            }
            if ($hand->user->hands->count() == 1) {
                $hand->user->participant->is_screw = true;
                $hand->user->participant->save();
            }
            return redirect()->route('ermy', $hand->id);
        } else {
            $lastCardInMyHand = Auth::user()->hands->sortByDesc('index')->first();
            $awlElkomaElmkshofa->user_id = $hand->user_id;
            $awlElkomaElmkshofa->index = $lastCardInMyHand->index + 1;
            $awlElkomaElmkshofa->save();
            $hand->game->endTurn();
            return redirect()->route('home')->with('error', 'ابلع');
        }
    }
    public function bdel(Hand $hand)
    {
        return View('cards.bdel', [
            'bdelWithHand' => $hand,
            'user' => Auth::user()
        ]);
    }
    public function bdelWith(Hand $hand, $index)
    {
        $userHand = Auth::user()->hands->where('index', $index)->first();
        $cardId = $hand->card_id;
        $hand->card_id = $userHand->card_id;
        $hand->save();
        $userHand->card_id = $cardId;
        $userHand->save();
        if ($hand->user_id == 1) {
            $hand->user_id = Auth::user()->id;
            $hand->save();
            return redirect()->route('ermy', $hand->id);
        } elseif (Auth::user()->participant->skill == 'KhodWHat') {
            Auth::user()->participant->endSkill();
        }
        $hand->game->endTurn();
        return redirect()->route('home');
    }

    public function screw(Participant $participant)
    {
        $participant->is_screw = true;
        $participant->save();
        $participant->game->endTurn();
        return redirect()->route('home');
    }
    public function endSkill(Participant $participant) //useless
    {
        $participant->endSkill();
        return redirect()->route('home');
    }
}