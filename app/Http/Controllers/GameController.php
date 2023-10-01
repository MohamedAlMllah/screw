<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Hand;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return View('games.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'score' => 'required|numeric',
            'password' => 'required',
        ]);

        $game = new Game();
        $game->admin_id = Auth::user()->id;
        $game->lose_score = $request->score;
        $game->password = $request->password;
        $game->save();

        $score = new Score();
        $score->user_id = Auth::user()->id;
        $score->game_id = $game->id;
        $score->turn = true;
        $score->save();

        $game->intialize();

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('home');
    }

    public function join(Game $game, Request $request)
    {
        if ($request->password != $game->password)
            return redirect()->route('home')->with('error', 'wrong password');
        if (Auth::user()->score)
            return redirect()->route('home')->with('error', 'You are already in game');
        if ($game->scores->count() == 2)
            return redirect()->route('home')->with('error', 'This game is full');

        $score = new Score();
        $score->user_id = Auth::user()->id;
        $score->game_id = $game->id;
        $score->save();
        $game = Game::where('id', $game->id)->firstOrFail();
        if ($game->scores->count() == 2)
            $game->startRound();
        return redirect()->route('home');
    }
    public function leave(Game $game)
    {
        if (Auth::user()->score->game != $game)
            return redirect()->route('home')->with('error', 'You are not playing this game');

        $score = Score::where('user_id', Auth::user()->id);
        $score->delete();
        $scores = Score::where('game_id', $game->id);
        foreach ($scores as $score) {
            $score->score = 0;
        }

        return redirect()->route('home');
    }

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

    public function screw(Score $score)
    {
        $score->screw = true;
        $score->save();
        $score->game->endTurn();
        return redirect()->route('home');
    }
}
