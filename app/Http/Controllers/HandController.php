<?php

namespace App\Http\Controllers;

use App\Models\Hand;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandController extends Controller
{
    public function ekshif(Hand $hand)
    {
        return View('cards.kshf', [
            'awlElkomaElmqlopa' => $hand,
            'user' => Auth::user()
        ]);
    }
    public function ermy(Hand $hand)
    {
        $hand->user_id = 2;
        $hand->index = $hand->game->getAwlElkomaElmkshofa()->index + 1;
        $hand->save();
        $hand->game->endTurn();
        return redirect()->route('home');
    }
    public function bsra(Hand $hand)
    {
        $awlElkomaElmkshofa = $hand->game->getAwlElkomaElmkshofa();
        if ($hand->card->id == $awlElkomaElmkshofa->card->id || abs($hand->card->value - $awlElkomaElmkshofa->card->value) == 25) {
            $myHands = Hand::where('user_id', $hand->user_id)->where('index', '>', $hand->index)->get();
            foreach ($myHands as $myHand) {
                $myHand->index--;
                $myHand->save();
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
    public function bdel(Hand $hand, Request $request)
    {
        $index = 1;
        foreach (Auth::user()->hands->sortBy('index') as $userHand) {
            if ($index == $request->order) {
                $cardId = $userHand->card_id;
                $userHand->card_id = $hand->card_id;
                $userHand->save();
                $hand->card_id = $cardId;
                $hand->save();
                if ($hand->user_id == 1) {
                    return redirect()->route('ermy', $hand->id);
                }
                $hand->game->endTurn();
                return redirect()->route('home');
            }
            $index++;
        }
    }

    public function screw(Participant $participant)
    {
        $participant->is_screw = true;
        $participant->save();
        $participant->game->endTurn();
        return redirect()->route('home');
    }
}
