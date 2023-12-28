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
        $participant = Auth::user()->participant;
        $kaabDayer = json_decode($participant->kaab_dayer, true);
        $skill = $participant->skill;
        if (
            (!$participant->is_turn && $participant->skill != 'showTwoCards') ||
            $participant->skill == 'normal' && $hand->user_id != 1 ||
            $participant->skill == 'kaabDayer' && !in_array($hand->user_id, $kaabDayer)
        ) {
            return redirect()->route('home')->with('error', "Don't Cheat");
        }
        if ($hand->user_id == 1) {
            $participant->skill = 'kshfElkoma';
            $participant->save();
        }
        if ($skill == 'showTwoCards') {
            $participant->skill = 'normal';
            $participant->save();
            $hand1 = $hand->user->hands->where('index', 1)->first();
            $hand2 = $hand->user->hands->where('index', 2)->first();
        } elseif ($hand->user_id != 1) {
            $announcement = Announcement::where('game_id', $hand->game_id)->orderBy('id', 'DESC')->first();
            if (Auth::user()->id == $hand->user_id) {
                $announcement->text .= ' w kshaf carto trtibo <u>' . $hand->index . '</u>';
            } else {
                $announcement->text .= ' w kshaf cart <p style="display:inline" class="text-success"><b>' . $hand->user->name . '</b></p> trtibo <u>' . $hand->index . '</u>';
            }
            $announcement->save();

            if (count($kaabDayer) > 0) {
                $participant->kaab_dayer = array_values(array_diff($kaabDayer, array($hand->user_id)));
                $participant->save();
                if (count($kaabDayer) == 1) {
                    $participant->endSkill(); //here
                }
            } else {
                $participant->endSkill(); //and here last play in round  
            }
        }
        return View('cards.kshf', [
            'hand' => $hand,
            'hand1' => $hand1 ?? null,
            'hand2' => $hand2 ?? null,
            'skill' => $skill,
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
            $announcement->save();
            Auth::user()->participant->endSkill();
        } elseif ($userId == Auth::user()->id) {
            if ($awlElkomaElmkshofa == $hand->card->name) {
                $announcement->text .= '<p style="display:inline" class="text-success"><b>' . Auth::user()->name . '</b></p> bsr cart <p style="display:inline" class="text-primary">' . $hand->card->name . '</p> trtibo <u>' . $index . '</u>';
            } else {
                $announcement->text .= '<p style="display:inline" class="text-success"><b>' . Auth::user()->name . '</b></p> bsr cart <p style="display:inline" class="text-primary">' . $hand->card->name . '</p> trtibo <u>' . $index . '</u> 3la cart <p style="display:inline" class="text-primary">' . $awlElkomaElmkshofa . '</p>';
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
        $participant = Auth::user()->participant;
        if ($participant->skill == 'kshfElkoma') {
            return redirect()->route('ekshif', $participant->game->getAwlElkomaElmqlopa()->id)->with('error', "Don't Cheat");
        }
        if (!$participant->is_turn) {
            return redirect()->route('home')->with('error', "Don't Cheat");
        }
        $awlElkomaElmkshofa = $hand->game->getAwlElkomaElmkshofa();
        if ($hand->card->id == $awlElkomaElmkshofa->card->id || abs($hand->card->value - $awlElkomaElmkshofa->card->value) == 25 || $hand->user->participant->skill == 'bsra') {

            $myHands = Hand::where('user_id', $hand->user_id)->where('index', '>', $hand->index)->get();
            foreach ($myHands as $myHand) {
                $myHand->index--;
                $myHand->save();
            }
            if ($hand->user->hands->count() == 1) {
                $hand->user->participant->is_screw = 2;
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
        $participant = Auth::user()->participant;
        if ($participant->skill == 'kshfElkoma' && $hand->id != $participant->game->getAwlElkomaElmqlopa()->id) {
            return redirect()->route('ekshif', $participant->game->getAwlElkomaElmqlopa()->id)->with('error', "Don't Cheat");
        }
        if (!$participant->is_turn) {
            return redirect()->route('home')->with('error', "Don't Cheat");
        }
        if ($participant->skill == 'kshfElkoma') {
            $participant->skill = 'normal';
            $participant->save();
        }
        return View('cards.bdel', [
            'bdelWithHand' => $hand,
            'user' => Auth::user()
        ]);
    }
    public function bdelWith(Hand $hand, Hand $myHand)
    {
        $participant = Auth::user()->participant;
        if (!$participant->is_turn) {
            return redirect()->route('home')->with('error', "Don't Cheat");
        }
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
            $announcement = Announcement::where('game_id', $hand->game_id)->orderBy('id', 'DESC')->first();
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
        if ($participant->skill != 'normal' || !$participant->is_turn) {
            return redirect()->route('home')->with('error', "Don't Cheat");
        }
        $participant->is_screw = 1;
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
        return redirect()->route('home');
    }
}
