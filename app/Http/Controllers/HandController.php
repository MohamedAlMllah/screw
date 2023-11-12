<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Hand;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;

class HandController extends Controller
{
    public function ekshif(Hand $hand)
    {
        $participant = $hand->user->participant;
        if ($participant && $participant->skill == 'showTwoCards') {
            $hand1 = $hand->user->hands->where('index', 1)->first();
            $hand2 = $hand->user->hands->where('index', 2)->first();
        } elseif ($hand->user_id != 1) {
            $announcement = Announcement::orderBy('id', 'DESC')->first();
            if (Auth::user()->id == $hand->user_id) {
                $announcement->text .= ' w kshaf carto trtibo <u>' . $hand->index . '</u>';
            } else {
                $announcement->text .= ' w kshaf cart <p style="display:inline" class="text-success"><b>' . $hand->user->name . '</b></p> trtibo <u>' . $hand->index . '</u>';
            }
            $announcement->save();
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
        $awlElkomaElmkshofa = $hand->game->getAwlElkomaElmkshofa()->card->name;
        $index = $hand->index;
        $hand->user_id = 2;
        $hand->index = $hand->game->getAwlElkomaElmkshofa()->index + 1;
        $hand->save();
        $participant = Participant::where('user_id', Auth::user()->id)->first();
        $announcement = new Announcement();
        $announcement->game_id = $hand->game_id;
        $announcement->user_id = Auth::user()->id;
        if ($userId == 1) {
            $announcement->text = '<p style="display:inline" class="text-success"><b>' . Auth::user()->name . '</b></p> rma cart <p style="display:inline" class="text-primary">' . $hand->card->name . '</p> mn el-koma ';
            $announcement->save();
            if ($hand->card_id >= 9 && $hand->card_id <= 10) {
                $participant->skill = 'bossFeWrqtak';
            } elseif ($hand->card_id >= 11 && $hand->card_id <= 12) {
                $participant->skill = 'bossFeWrqtGherak';
            } elseif ($hand->card_id == 13) { //bsra
                $participant->skill = 'bsra';
            } elseif ($hand->card_id == 14) { //k3b dayer
                $participant->skill = 'kaabDayer';
                $participant->kaab_dayer = $participant->game->participants->pluck('user_id');
            } elseif ($hand->card_id == 15) { //5od what
                $participant->skill = 'KhodWHat';
            } else {
                Auth::user()->participant->endSkill();
            }
        }
        if ($participant->skill == 'bsra' && $userId == Auth::user()->id) {
            $announcement = Announcement::orderBy('id', 'DESC')->first();
            $announcement->text .= ' b3den rma cart <p style="display:inline" class="text-primary">' . $hand->card->name . '</p> trtibo <u>' . $index . '</u>';
            Auth::user()->participant->endSkill();
        } elseif ($userId == Auth::user()->id) {
            if ($awlElkomaElmkshofa == $hand->card->name) {
                $announcement->text .= '<p style="display:inline" class="text-success"><b>' . Auth::user()->name . '</b></p> bsr cart <p style="display:inline" class="text-primary">' . $hand->card->name . '</p> trtibo <u>' . $index . '</u>';
            } else {
                $announcement->text .= '<p style="display:inline" class="text-success"><b>' . Auth::user()->name . '</b></p> bsr cart <p style="display:inline" class="text-primary">' . $hand->card->name . '</p> trtibo <u>' . $index . '</u> 3la cart ' . $awlElkomaElmkshofa;
            }
            $announcement->save();
            $hand->game->endTurn();
        } elseif ($userId == 2) { //avoid active skill
            $announcement->text = '<p style="display:inline" class="text-success"><b>' . Auth::user()->name . '</b></p> s7b mn el-koma w rma cart <p style="display:inline" class="text-primary">' . $hand->card->name . '</p> trtibo <u>' . $index . '</u>';
            $announcement->save();
            $hand->game->endTurn();
        }
        $participant->save();

        return redirect()->route('home');
    }
    public function bsra(Hand $hand)
    {
        if ($hand->user->participant->skill != 'bsra') {
        }

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

            $announcement = new Announcement();
            $announcement->game_id = $hand->game_id;
            $announcement->user_id = Auth::user()->id;
            $announcement->text = '<p style="display:inline" class="text-success"><b>' . Auth::user()->name . '</b></p> 7awl ybsr cart <p style="display:inline" class="text-primary">' . $hand->card->name . '</p> trtibo <u>' . $hand->index . '</u> 3la cart <p style="display:inline" class="text-primary">' . $awlElkomaElmkshofa->card->name . '</p> trtibo bqa <u>' . $awlElkomaElmkshofa->index . '</u>';
            $announcement->save();
            $hand->game->endTurn();
            return redirect()->route('home')->with('error', 'ebl3');
        }
    }
    public function bdel(Hand $hand)
    {
        return View('cards.bdel', [
            'bdelWithHand' => $hand,
            'user' => Auth::user()
        ]);
    }
    public function bdelWith(Hand $hand, Hand $myHand)
    {
        $cardId = $hand->card_id;
        $hand->card_id = $myHand->card_id;
        $hand->save();
        $myHand->card_id = $cardId;
        $myHand->save();
        if ($hand->user_id == 1) { //avoid skill
            $hand->user_id = 2;
            $hand->index = $myHand->index; //trtep ely atrmy
            $hand->save();
            return redirect()->route('ermy', $hand->id);
        }
        $announcement = new Announcement();
        $announcement->game_id = $hand->game_id;
        $announcement->user_id = Auth::user()->id;
        if (Auth::user()->participant->skill == 'KhodWHat') {
            $announcement = Announcement::orderBy('id', 'DESC')->first();
            $announcement->text .= ' w bdel carto trtibo <u>' . $myHand->index . '</u> b cart <p style="display:inline" class="text-success"><b>' . $hand->user->name . '</b></p> trtibo <u>' . $hand->index . '</u>';
            $announcement->save();
            Auth::user()->participant->endSkill();
        } else {
            $announcement->text = '<p style="display:inline" class="text-success"><b>' . Auth::user()->name . '</b></p> bdel carto <p style="display:inline" class="text-primary">' . $hand->card->name . '</p> trtibo <u>' . $myHand->index . '</u> b cart <p style="display:inline" class="text-primary">' . $myHand->card->name . '</p> mn el-ard';
            $announcement->save();
            $hand->game->endTurn();
        }
        return redirect()->route('home');
    }

    public function screw(Participant $participant)
    {
        $participant->is_screw = true;
        $participant->save();
        $announcement = new Announcement();
        $announcement->game_id = $participant->game_id;
        $announcement->user_id = Auth::user()->id;
        $announcement->text = '<p style="display:inline" class="text-success"><b>' . Auth::user()->name . '</b></p><p style="display:inline" class="text-danger"> screw </p>';
        $announcement->save();
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
