<div class="row mb-5 align-items-top gx-2 gx-lg-3">
    @if($numberOfPlayers>2)
    <div class="col-3">
        @if($numberOfPlayers>4)
        <div class="row text-center mb-1">
            <h4>{{$game->getPlayerInOrder($numberOfPlayers-2,$user)->name}}&nbsp;(&nbsp;{{$game->getPlayerInOrder($numberOfPlayers-2,$user)->totalScore()}}&nbsp;)</h4>
        </div>
        @foreach($game->getPlayerInOrder($numberOfPlayers-2,$user)->hands->sortBy('index') as $hand)
        <div class="row gx-0 align-items-top text-center">
            <div class="col-1">
                {{$hand->index}}
            </div>
            <div class="col-8">
                @if($game->getPlayerInOrder($numberOfPlayers-2,$user)->hands->count() == $hand->index)
                <img src="{{asset('images/cards/backHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @else
                <img src="{{asset('images/cards/backHalfHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @endif
            </div>
            <div class="col-3">
                @if($user->participant->is_turn && ($skill == 'bossFeWrqtGherak' || ($skill == 'kaabDayer' && in_array($game->getPlayerInOrder($numberOfPlayers-2,$user)->id, json_decode($user->participant->kaab_dayer, true)))))
                <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary border-0" style="padding: 0;">&nbsp;اكشف&nbsp;</a>
                @elseif($user->participant->is_turn && $skill == 'KhodWHat')
                <a href="{{ route('bdel', [$hand->id]) }}" type="button" class="btn btn-outline-success border-0" style="padding: 0;">&nbsp;بـــدل&nbsp;</a>
                @endif
            </div>
        </div>
        @endforeach
        @endif

        <div class="row text-center mt-5 mb-1">
            <h4>{{$game->getPlayerInOrder($numberOfPlayers-1,$user)->name}}&nbsp;(&nbsp;{{$game->getPlayerInOrder($numberOfPlayers-1,$user)->totalScore()}}&nbsp;)</h4>
        </div>
        @foreach($game->getPlayerInOrder($numberOfPlayers-1,$user)->hands->sortBy('index') as $hand)
        <div class="row gx-0 align-items-top text-center">
            <div class="col-1">
                {{$hand->index}}
            </div>
            <div class="col-8">
                @if($game->getPlayerInOrder($numberOfPlayers-1,$user)->hands->count() == $hand->index)
                <img src="{{asset('images/cards/backHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @else
                <img src="{{asset('images/cards/backHalfHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @endif
            </div>
            <div class="col-3">
                @if($user->participant->is_turn && ($skill == 'bossFeWrqtGherak' || ($skill == 'kaabDayer' && in_array($game->getPlayerInOrder($numberOfPlayers-1,$user)->id, json_decode($user->participant->kaab_dayer, true)))))
                <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary border-0" style="padding: 0;">&nbsp;اكشف&nbsp;</a>
                @elseif($user->participant->is_turn && $skill == 'KhodWHat')
                <a href="{{ route('bdel', [$hand->id]) }}" type="button" class="btn btn-outline-success border-0" style="padding: 0;">&nbsp;بـــدل&nbsp;</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="col-3">&nbsp;</div>
    @endif
    <div class="col-2 align-self-center">
        <div class="text-center">
            {{$elkomaElmqlopaCount}} </br> كارت
            <img src="{{asset('images/cards/back.png')}}" class="card-img-top img-fluid" alt="...">
            @if($user->participant->is_turn && $skill == 'normal')
            <a href="{{ route('ekshif', [$game->getAwlElkomaElmqlopa()->id]) }}" class="btn btn-outline-primary mt-1">اكشف</a>
            @endif
        </div>
    </div>
    <div class="col-2 text-center align-self-center">
        &nbsp;
        @if($game->canScrew($user))
        <a href="{{ route('screw', [$user->participant->id]) }}" class="btn btn-outline-danger fs-6">سكرو</a>
        @endif
        @if($game->screwPlayer())
        <div class="alert alert-danger" role="alert">
            {{$game->screwPlayer()->name}} سكرو
        </div>
        @endif
        @if($skill=='showTwoCards' && floor($game->turns / $game->participants->count()) == 0 && $user->participant->is_turn)
        <a href="{{ route('ekshif', [$user->hands->first()->id]) }}" class="btn btn-outline-primary">اكشف كارتين</a>
        @endif
        &nbsp;
    </div>
    <div class="col-2 align-self-center">
        <div class="text-center">
            {{$elkomaElmkshofaCount}} </br> كارت
            <img src="{{asset($awlElkomaElmkshofa->card->image)}}" class="card-img-top img-fluid" alt="...">
            @if($user->participant->is_turn && $skill == 'normal')
            <a href="{{ route('bdel', [$awlElkomaElmkshofa->id]) }}" class="btn btn-outline-success mt-1">بـــدل</a>
            @endif
        </div>
    </div>
    @if($numberOfPlayers>2)
    <div class="col-3 align-self-top">
        @if($numberOfPlayers>4)
        <div class="row text-center mb-1">
            <h4>{{$game->getPlayerInOrder(2,$user)->name}}&nbsp;(&nbsp;{{$game->getPlayerInOrder(2,$user)->totalScore()}}&nbsp;)</h4>
        </div>
        @foreach($game->getPlayerInOrder(2,$user)->hands->sortBy('index') as $hand)
        <div class="row gx-0 text-center">
            <div class="col-3">
                @if($user->participant->is_turn && ($skill == 'bossFeWrqtGherak' || ($skill == 'kaabDayer' && in_array($game->getPlayerInOrder(2,$user)->id, json_decode($user->participant->kaab_dayer, true)))))
                <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary border-0" style="padding: 0;">&nbsp;اكشف&nbsp;</a>&nbsp;
                @elseif($user->participant->is_turn && $skill == 'KhodWHat')
                <a href="{{ route('bdel', [$hand->id]) }}" class="btn btn-outline-success border-0" style="padding: 0;">&nbsp;بـــدل&nbsp;</a>&nbsp;
                @endif
            </div>
            <div class="col-8">
                @if($game->getPlayerInOrder(2,$user)->hands->count() == $hand->index)
                <img src="{{asset('images/cards/backHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @else
                <img src="{{asset('images/cards/backHalfHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @endif
            </div>
            <div class="col-1">
                {{$hand->index}}
            </div>
        </div>
        @endforeach
        @endif

        <div class="row text-center mt-2 mb-1">
            <h4>{{$game->getPlayerInOrder(1,$user)->name}}&nbsp;(&nbsp;{{$game->getPlayerInOrder(1,$user)->totalScore()}}&nbsp;)</h4>
        </div>
        @foreach($game->getPlayerInOrder(1,$user)->hands->sortBy('index') as $hand)
        <div class="row gx-0 text-center">
            <div class="col-3">
                @if($user->participant->is_turn && ($skill == 'bossFeWrqtGherak' || ($skill == 'kaabDayer' && in_array($game->getPlayerInOrder(1,$user)->id, json_decode($user->participant->kaab_dayer, true)))))
                <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary border-0" style="padding: 0;">&nbsp;اكشف&nbsp;</a>&nbsp;
                @elseif($user->participant->is_turn && $skill == 'KhodWHat')
                <a href="{{ route('bdel', [$hand->id]) }}" class="btn btn-outline-success border-0" style="padding: 0;">&nbsp;بـــدل&nbsp;</a>&nbsp;
                @endif
            </div>
            <div class="col-8">
                @if($game->getPlayerInOrder(1,$user)->hands->count() == $hand->index)
                <img src="{{asset('images/cards/backHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @else
                <img src="{{asset('images/cards/backHalfHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @endif
            </div>
            <div class="col-1">
                {{$hand->index}}
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="col-3">&nbsp;</div>
    @endif
</div>