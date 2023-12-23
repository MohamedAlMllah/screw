<div class="text-end mb-3">
    <div class="btn-group" role="group" aria-label="Basic outlined example">
        <a href="{{ route('summary', [$game->id]) }}" class="btn btn-outline-primary">Summary</a>
        <a href="{{ route('leave', [$game->id]) }}" onclick="$('#formLeave').attr('action', this.href)" type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#leaveModal">Leave Game</a>
    </div>
</div>

@if($game->participants->count() < $game->number_of_players) <h1>
        waiting for other players to join {{$game->participants->count()}} / {{$game->number_of_players}}
    </h1>
    <meta http-equiv="refresh" content="5">
    @elseif($game->participants->count() == $game->number_of_players && $game->starting_covered_cards == 'not selected')
    <h1>
        Waiting for the admin to start round.
    </h1>
    @else
    @include('home.top')
    @include('home.center')
    @include('home.bottom')

    @endif
    @if(!$user->participant->is_turn || $playersNotViewedTwoCards->count())
    <meta http-equiv="refresh" content="5">
    @endif