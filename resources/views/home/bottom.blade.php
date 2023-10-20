<div class="row gx-0">
    <div class="col-4">&nbsp;</div>
    @foreach($user->hands->sortBy('index') as $hand)
    @if($user->hands->count() == $hand->index)
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
                <!--<img src="{{asset($hand->card->image)}}" class="card-img-top img-fluid" alt="...">-->
                @if($user->participant->is_turn && $skill == 'normal')
                <a href="{{ route('bsra', [$hand->id]) }}" class="btn btn-outline-warning mt-1">بصرة</a>
                @elseif($user->participant->is_turn && ($skill == 'bossFeWrqtak' || ($skill == 'kaabDayer' && in_array($user->id, json_decode($user->participant->kaab_dayer, true)))))
                <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary mt-1">اكشف</a>
                @elseif($user->participant->is_turn && $skill == 'bsra')
                <a href="{{ route('bsra', [$hand->id]) }}" class="btn btn-outline-warning mt-1">بصرة</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>