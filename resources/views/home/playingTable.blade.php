<div class="justify-content-center d-flex mb-3">
    <h4 class="ms-auto">Score X {{$game->multiple_score}}</h4>
    <div class="btn-group ms-auto" role="group" aria-label="Basic outlined example">
        <a href="{{ route('summary', [$game->id]) }}" class="btn btn-outline-primary">Summary</a>
        <a href="{{ route('leave', [$game->id]) }}" onclick="$('#formLeave').attr('action', this.href)" type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#leaveModal">Leave Game</a>
    </div>
</div>

@if(count($gameParticipants) < $game->number_of_players)
    @foreach($gameParticipants as $participant)
    <h3 class="text-center">
        <p style="display:inline" class="text-success"><b>{{$participant['name']}}</b></p> joined the game.<br>
    </h3>
    @endforeach
    <h1 class="text-center mt-5">
        waiting for other players to join {{count($gameParticipants)}} / {{$game->number_of_players}}
    </h1>
    <meta http-equiv="refresh" content="5">
    @elseif(count($gameParticipants) == $game->number_of_players && $game->multiple_score == 0)
    <h1>
        Waiting for the admin to start round.
    </h1>
    @else
    @include('home.top')
    @include('home.center')
    @include('home.bottom')

    @endif
    @if(!$gameParticipants[0]['isTurn'] || $playersNotViewedTwoCardsCount)
    <meta http-equiv="refresh" content="5">
    @endif