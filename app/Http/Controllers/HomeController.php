<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Hand;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $participant = Auth::user()->participant;
        if ($participant && $participant->game->participants->count() == $participant->game->number_of_players) {
            if ($participant->game->gameIsFinished()) {
                return redirect()->route('summary', $participant->game->id);
            }
            $elkomaElmqlopaCount = Hand::where('game_id', $participant->game->id)->where('user_id', 1)->count();
            $awlElkomaElmkshofa = $participant->game->getAwlElkomaElmkshofa();
            $awlElkomaElmqlopa = $participant->game->getAwlElkomaElmqlopa();
            $elkomaElmkshofaCount = Hand::where('game_id', $participant->game->id)->where('user_id', 2)->count();
            $skill = $participant->skill;
            $numberOfPlayers = $participant->game->participants->count();
        }
        return view('home', [
            'games' => Game::all(),
            'user' => Auth::user(),
            'game' => $participant->game ?? null,
            'numberOfPlayers' => $numberOfPlayers ?? 0,
            'skill' => $skill ?? 'normal',
            'awlElkomaElmkshofa' => $awlElkomaElmkshofa ?? null,
            'awlElkomaElmqlopa' => $awlElkomaElmqlopa ?? null,
            'elkomaElmkshofaCount' => $elkomaElmkshofaCount ?? null,
            'elkomaElmqlopaCount' => $elkomaElmqlopaCount ?? null,
        ]);
    }
}
