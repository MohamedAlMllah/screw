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
        }
        return View('cards.kshf', [
            'hand' => $hand,
            'hand1' => $hand1 ?? null,
            'hand2' => $hand2 ?? null,
            'skill' => Auth::user()->participant->skill,
            'user' => Auth::user()
        ]);
    }
    public function ermy(Hand $hand)
    {
        $userId = $hand->user_id;
        $hand->user_id = 2;
        $hand->index = $hand->game->getAwlElkomaElmkshofa()->index + 1;
        $hand->save();
        $participant = Participant::where('user_id', Auth::user()->id)->first();
        if ($userId == 1 && $hand->card_id >= 9 && $hand->card_id <= 10) {
            $participant->skill = 'bossFeWrqtak';
        } elseif ($userId == 1 && $hand->card_id >= 11 && $hand->card_id <= 12) {
            $participant->skill = 'bossFeWrqtGherak';
        } elseif ($userId == 1 && $hand->card_id == 13) { //bsra
            $participant->skill = 'bsra';
        } elseif ($userId == 1 && $hand->card_id == 14) { //k3b dayer
            $participant->skill = 'kaabDayer';
            $participant->kaab_dayer = $participant->game->participants->where('user_id', '!=', $participant->user_id)->pluck('user_id');
        } elseif ($userId == 1 && $hand->card_id == 15) { //5od what
            $participant->skill = 'KhodWHat';
        } else {
            Auth::user()->participant->endSkill();
        }
        $participant->save();
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
        }
        if (Auth::user()->participant->skill == 'KhodWHat') {
            Auth::user()->participant->endSkill();
        } else {
            $hand->game->endTurn();
        }
        return redirect()->route('home');
    }

    public function screw(Participant $participant)
    {
        $participant->is_screw = true;
        $participant->save();
        $participant->game->endTurn();
        return redirect()->route('home');
    }
    public function endSkill(hand $hand)
    {
        $kaabDayer = json_decode(Auth::user()->participant->kaab_dayer, true);
        if (count($kaabDayer) > 0) {
            Auth::user()->participant->kaab_dayer = array_diff($kaabDayer, array($hand->user_id));
            Auth::user()->participant->save();
            if (count($kaabDayer) == 1) {
                Auth::user()->participant->endSkill();
            }
        } else {
            Auth::user()->participant->endSkill();
        }
        return redirect()->route('home');
    }
}
