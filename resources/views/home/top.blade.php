@if($numberOfPlayers % 2 == 0)
<div class="row text-center mb-1">
    @if($game->getPlayerInOrder($numberOfPlayers/2,$user)->participant->is_turn)
    <div style="font-size: 50px; color: red; margin-bottom: -20px;">&#11206;</div>
    @endif
    <h4>{{$game->getPlayerInOrder($numberOfPlayers/2,$user)->name}}&nbsp;(&nbsp;{{$game->getPlayerInOrder($numberOfPlayers/2,$user)->totalScore()}}&nbsp;)</h4>
</div>
<div class="row mb-5 gx-0">
    <div class="col-5">&nbsp;</div>
    <div class="col-7 row gx-0">
        @foreach($game->getPlayerInOrder($numberOfPlayers/2,$user)->hands->sortBy('index') as $hand)
        @if($game->getPlayerInOrder($numberOfPlayers/2,$user)->hands->count() == $hand->index || $hand->index == 5)
        <div class="col-4">
            @else
            <div class="col-2">
                @endif
                <div class="text-center">
                    {{$hand->index}}
                    @if($game->getPlayerInOrder($numberOfPlayers/2,$user)->hands->count() == $hand->index || $hand->index == 5)
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
    </div>
    @endif