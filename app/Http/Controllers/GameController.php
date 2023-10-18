<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Participant;
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
        $game->number_of_players = $request->numberOfPlayers;
        $game->save();

        $score = new Participant();
        $score->user_id = Auth::user()->id;
        $score->game_id = $game->id;
        $score->is_turn = true;
        $score->save();

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
        if ($game->participants->count() == $game->number_of_players)
            return redirect()->route('home')->with('error', 'This game is full');

        $participant = new Participant();
        $participant->user_id = Auth::user()->id;
        $participant->game_id = $game->id;
        $participant->save();
        $game = Game::where('id', $game->id)->first();
        if ($game->participants->count() == $game->number_of_players) {
            $game->intialize();
            $game->startRound($game->admin);
        }
        return redirect()->route('home');
    }
    public function leave(Game $game)
    {
        if (Auth::user()->participant && Auth::user()->participant->game != $game) {
            return redirect()->route('home')->with('error', 'You are not playing this game');
        }
        if ($game->participants->count() > 1 && Auth::user()->id == $game->admin_id) {
            $game->admin_id = $game->getPlayerInOrder(1, Auth::user())->id;
            $game->save();
        }

        $participant = Participant::where('user_id', Auth::user()->id);
        foreach ($game->scores as $score) {
            $score->delete();
        }
        foreach ($game->hands as $hand) {
            $hand->delete();
        }
        $participant->delete();

        return redirect()->route('home');
    }

    public function summary(Game $game)
    {
        $scores = [];
        $totalScores = [];
        $playersNames = [];
        foreach ($game->participants as $participant) {
            array_push($scores, $participant->user->scores);
            array_push($totalScores, $participant->user->totalScore());
            array_push($playersNames, $participant->user->name);
        }
        return View('games.summary', [
            'scores' => $scores,
            'totalScores' => $totalScores,
            'playersNames' => $playersNames,
            'numberOfPlayers' => $game->participants->count(),
            'numberOfRounds' => $game->scores->max('round')
        ]);
    }
}
