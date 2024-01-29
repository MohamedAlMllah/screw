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
        $games = [];
        foreach (Game::all()->where('is_finished', false) as $game) {
            $gameData = [
                'admin' => $game->admin,
                'game' => $game,
                'participantsCount' => $game->participants()->count()
            ];
            array_push($games, $gameData);
        }
        $participants = [];
        if ($participant) {
            $currentParticipantIsFound = 0;
            foreach ($participant->game->participants as $participantFromList) {
                if ($participant->id != $participantFromList->id && !$currentParticipantIsFound) {
                    continue;
                } else {
                    $currentParticipantIsFound = 1;
                }
                $participantData = [
                    'name' => $participantFromList->user->name,
                    'hands' => $participantFromList->user->hands->sortBy('index'),
                    'participant' => $participantFromList,
                    'totalScore' => $participantFromList->user->totalScore($participant->game),
                    'skill' => $participantFromList->skill,
                    'isTurn' => $participantFromList->is_turn
                ];
                array_push($participants, $participantData);
            }
            foreach ($participant->game->participants as $participantFromList) {
                if ($participant->id == $participantFromList->id) {
                    break;
                }
                $participantData = [
                    'name' => $participantFromList->user->name,
                    'hands' => $participantFromList->user->hands->sortBy('index'),
                    'participant' => $participantFromList,
                    'totalScore' => $participantFromList->user->totalScore($participant->game),
                    'skill' => $participantFromList->skill,
                    'isTurn' => $participantFromList->is_turn
                ];
                array_push($participants, $participantData);
            }
        }

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
            $playersNotViewedTwoCardsCount = Participant::where('game_id', $participant->game->id)->where('skill', 'showTwoCards')->get()->count();
            if ($game->screwPlayer()) {
                if ($game->screwPlayer()->participant->is_screw == 1) {
                    $screwPlayerType = ' Screw';
                } else {
                    $screwPlayerType = ' Finished Cards';
                }
                $screwPlayer = [
                    'name' => $game->screwPlayer()->name,
                    'message' => $screwPlayerType
                ];
            }
            $canScrew = $game->canScrew(Auth::user());
        }
        return view('home', [
            'games' => $games,
            'user' => Auth::user(),
            'participant' => Auth::user()->participant,
            'game' => $participant->game ?? null,
            'gameParticipants' => $participants ?? null,
            'numberOfPlayers' => $numberOfPlayers ?? 0,
            'playersNotViewedTwoCardsCount' => $playersNotViewedTwoCardsCount ?? collect(),
            'canScrew' => $canScrew ?? false,
            'screwPlayer' => $screwPlayer ?? [],
            'skill' => $skill ?? 'normal',
            'announcements' => $announcements ?? null,
            'awlElkomaElmkshofa' => $awlElkomaElmkshofa ?? null,
            'awlElkomaElmkshofaCard' => $awlElkomaElmkshofa->card ?? null,
            'awlElkomaElmqlopa' => $awlElkomaElmqlopa ?? null,
            'elkomaElmkshofaCount' => $elkomaElmkshofaCount ?? null,
            'elkomaElmqlopaCount' => $elkomaElmqlopaCount ?? null,
        ]);
    }
}
