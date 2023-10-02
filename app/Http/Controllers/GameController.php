<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Hand;
use App\Models\Participant;
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

        $score = new Participant();
        $score->user_id = Auth::user()->id;
        $score->game_id = $game->id;
        $score->is_turn = true;
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
        if (Auth::user()->participant)
            return redirect()->route('home')->with('error', 'You are already in game');
        if ($game->participants->count() == 2)
            return redirect()->route('home')->with('error', 'This game is full');

        $participant = new Participant();
        $participant->user_id = Auth::user()->id;
        $participant->game_id = $game->id;
        $participant->save();
        $game = Game::where('id', $game->id)->first();
        if ($game->participants->count() == 2) {
            $game->startRound();
        }
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
}
