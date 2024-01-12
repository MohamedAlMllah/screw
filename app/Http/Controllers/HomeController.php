<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Game;
use App\Models\Hand;
use App\Models\Participant;
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
        if ($participant && $participant->game->isFinished()) {
            return redirect()->route('summary', $participant->game->id);
        }
        if ($participant && $participant->round_is_end) {
            if (!$participant->game->participants->where('skill', '!=', 'normal')->count()) {
                return redirect()->route('showAllPlayersCards', $participant->game_id);
            }
        }
        if ($participant && $participant->game->admin_id == Auth::user()->id && $participant->game->multiple_score == 0 && $participant->game->participants->count() == $participant->game->number_of_players && !$participant->game->isFinished()) {
            return redirect()->route('roundOptions', $participant->game_id);
        }
        if ($participant && $participant->skill == 'kshfElkoma') {
            return redirect()->route('ekshif', $participant->game->getAwlElkomaElmqlopa()->id);
        }
        if ($participant && $participant->game->participants->count() == $participant->game->number_of_players) {
            $elkomaElmqlopaCount = Hand::where('game_id', $participant->game->id)->where('user_id', 1)->count();
            $awlElkomaElmkshofa = $participant->game->getAwlElkomaElmkshofa();
            $awlElkomaElmqlopa = $participant->game->getAwlElkomaElmqlopa();
            $elkomaElmkshofaCount = Hand::where('game_id', $participant->game->id)->where('user_id', 2)->count();
            $skill = $participant->skill;
            $numberOfPlayers = $participant->game->participants->count();
            $announcements = Announcement::where('game_id', $participant->game->id)->orderBy('id', 'DESC')->take($numberOfPlayers)->get();
            $playersNotViewedTwoCards = Participant::where('game_id', $participant->game->id)->where('skill', 'showTwoCards')->get();
        }
        return view('home', [
            'games' => Game::all()->where('is_finished', false),
            'user' => Auth::user(),
            'game' => $participant->game ?? null,
            'numberOfPlayers' => $numberOfPlayers ?? 0,
            'playersNotViewedTwoCards' => $playersNotViewedTwoCards ?? collect(),
            'skill' => $skill ?? 'normal',
            'announcements' => $announcements ?? null,
            'awlElkomaElmkshofa' => $awlElkomaElmkshofa ?? null,
            'awlElkomaElmqlopa' => $awlElkomaElmqlopa ?? null,
            'elkomaElmkshofaCount' => $elkomaElmkshofaCount ?? null,
            'elkomaElmqlopaCount' => $elkomaElmqlopaCount ?? null,
        ]);
    }
}
