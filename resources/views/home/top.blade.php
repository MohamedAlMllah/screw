@if($numberOfPlayers % 2 == 0)
<div class="row text-center mb-1">
    <h4>{{$game->getPlayerInOrder($numberOfPlayers/2,$user)->name}}&nbsp;(&nbsp;{{$game->getPlayerInOrder($numberOfPlayers/2,$user)->totalScore()}}&nbsp;)</h4>
</div>
<div class="row mb-5 gx-0">
    <div class="col-4">&nbsp;</div>
    @foreach($game->getPlayerInOrder($numberOfPlayers/2,$user)->hands->sortBy('index') as $hand)
    @if($game->getPlayerInOrder($numberOfPlayers/2,$user)->hands->count() == $hand->index)
    <div class="col-2">
        @else
        <div class="col-1">
            @endif
            <div class="text-center">
                {{$hand->index}}
                @if($game->getPlayerInOrder($numberOfPlayers/2,$user)->hands->count() == $hand->index)
                <img src="{{asset('images/cards/back.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @else
                <img src="{{asset('images/cards/backHalf.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @endif
                @if($user->participant->is_turn && ($skill == 'bossFeWrqtGherak' || ($skill == 'kaabDayer' && in_array($game->getPlayerInOrder($numberOfPlayers/2,$user)->id, json_decode($user->participant->kaab_dayer, true)))))
                <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary border-0 mt-1">اكشف</a>
                @elseif($user->participant->is_turn && $skill == 'KhodWHat')
                <a href="{{ route('bdel', [$hand->id]) }}" type="button" class="btn btn-outline-success border-0 mt-1">بـــدل</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif