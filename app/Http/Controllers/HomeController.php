<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Hand;
use App\Models\Score;
use Illuminate\Http\Request;
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
        
        $score = Auth::user()->score;
        if ($score && $score->game->scores->count() == 2) {
            if ($score->game->gameIsFinished()){
                return 'the game summary here';
            }
            $elkomaElmqlopaCount = Hand::where('game_id', $score->game->id)->where('user_id', 1)->count();
            $awlElkomaElmkshofa = $score->game->getAwlElkomaElmkshofa();
            $awlElkomaElmqlopa = $score->game->getAwlElkomaElmqlopa();
            $elkomaElmkshofaCount = Hand::where('game_id', $score->game->id)->where('user_id', 2)->count();
        }
        return view('home', [
            'games' => Game::all(),
            'user' => Auth::user(),
            'game' => $score->game ?? null,
            'awlElkomaElmkshofa' => $awlElkomaElmkshofa ?? null,
            'awlElkomaElmqlopa' => $awlElkomaElmqlopa ?? null,
            'elkomaElmkshofaCount' => $elkomaElmkshofaCount ?? null,
            'elkomaElmqlopaCount' => $elkomaElmqlopaCount ?? null,
        ]);
    }
}
