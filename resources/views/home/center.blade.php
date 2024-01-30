<div class="row mb-5 mt-n3 align-items-top gx-2 gx-lg-3">
    @if($numberOfPlayers>2)
    <div class="col-3">
        @if($numberOfPlayers>4)
        <div class="row text-center mb-1">
            @if($gameParticipants[$numberOfPlayers-2]['isTurn'])
            <div style="font-size: 30px; color: red; margin-bottom: -10px;"> &#9947;</div>
            @else
            &nbsp;
            @endif
            <h4>{{$gameParticipants[$numberOfPlayers-2]['name']}}&nbsp;(&nbsp;{{$gameParticipants[$numberOfPlayers-2]['totalScore']}}&nbsp;)</h4>
        </div>
        @foreach($gameParticipants[$numberOfPlayers-2]['hands'] as $hand)
        <div class="row gx-0 align-items-center pb-n1 text-center">
            <div class="col-1">
                {{$hand->index}}
            </div>
            <div class="col-8">
                @if(count($gameParticipants[$numberOfPlayers-2]['hands']) == $hand->index)
                <img src="{{asset('images/cards/backHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @else
                <img src="{{asset('images/cards/backHalfHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @endif
            </div>
            <div class="col-3">
                @if($gameParticipants[0]['isTurn'] && ($skill == 'bossFeWrqtGherak' || ($skill == 'kaabDayer' && in_array($gameParticipants[$numberOfPlayers-2]['participant']->user_id, json_decode($gameParticipants[0]['participant']->kaab_dayer, true)))))
                <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary border-0" style="padding: 0;">&nbsp;اكشف&nbsp;</a>
                @elseif($gameParticipants[0]['isTurn'] && $skill == 'KhodWHat')
                <a href="{{ route('bdel', [$hand->id]) }}" type="button" class="btn btn-outline-success border-0" style="padding: 0;">&nbsp;بـــدل&nbsp;</a>
                @endif
            </div>
        </div>
        @endforeach
        @endif

        <div class="row text-center mt-3 mb-1">
            @if($gameParticipants[$numberOfPlayers-1]['isTurn'])
            <div style="font-size: 30px; color: red; margin-bottom: -10px;"> &#9947;</div>
            @else
            &nbsp;
            @endif
            <h4>{{$gameParticipants[$numberOfPlayers-1]['name']}}&nbsp;(&nbsp;{{$gameParticipants[$numberOfPlayers-1]['totalScore']}}&nbsp;)</h4>
        </div>
        @foreach($gameParticipants[$numberOfPlayers-1]['hands'] as $hand)
        <div class="row gx-0 align-items-top text-center">
            <div class="col-1">
                {{$hand->index}}
            </div>
            <div class="col-8">
                @if(count($gameParticipants[$numberOfPlayers-1]['hands']) == $hand->index)
                <img src="{{asset('images/cards/backHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @else
                <img src="{{asset('images/cards/backHalfHorizontal.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                @endif
            </div>
            <div class="col-3">
                @if($gameParticipants[0]['isTurn'] && ($skill == 'bossFeWrqtGherak' || ($skill == 'kaabDayer' && in_array($gameParticipants[$numberOfPlayers-1]['participant']->user_id, json_decode($gameParticipants[0]['participant']->kaab_dayer, true)))))
                <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary border-0" style="padding: 0;">&nbsp;اكشف&nbsp;</a>
                @elseif($gameParticipants[0]['isTurn'] && $skill == 'KhodWHat')
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
            @if($gameParticipants[0]['isTurn'] && $skill == 'normal' && !$playersNotViewedTwoCardsCount)
            <a href="{{ route('ekshif', [$awlElkomaElmqlopa->id]) }}" class="btn btn-outline-primary mt-1">اكشف</a>
            @endif
        </div>
    </div>

    <div class="col-2 text-center align-self-center">
        &nbsp;
        @if($canScrew && $skill == 'normal')
        <a href="{{ route('screw', [$gameParticipants[0]['participant']->id]) }}" class="btn btn-outline-danger fs-6">سكرو</a>
        @endif
        @if($screwPlayer)
        <div class="alert alert-danger" role="alert">
            {{$screwPlayer['name'].$screwPlayer['message']}}
        </div>
        @endif
        @if($skill=='showTwoCards')
        <a href="{{ route('ekshif', [$gameParticipants[0]['hands'][0]->id]) }}" class="btn btn-outline-primary">اكشف كارتين</a>
        @endif
        &nbsp;
    </div>

    <div class="col-2 align-self-center">
        <div class="text-center">
            {{$elkomaElmkshofaCount}} </br> كارت
            <img src="{{asset($awlElkomaElmkshofaCard->image)}}" class="card-img-top img-fluid" alt="...">
            @if($gameParticipants[0]['isTurn'] && $skill == 'normal' && !$playersNotViewedTwoCardsCount)
            <a href="{{ route('bdel', [$awlElkomaElmkshofa->id]) }}" class="btn btn-outline-success mt-1">بـــدل</a>
            @endif
        </div>
    </div>

    @if($numberOfPlayers>2)
    <div class="col-3">
        @if($numberOfPlayers>4)
        <div class="row text-center mb-1">
            @if($gameParticipants[2]['isTurn'])
            <div style="font-size: 30px; color: red; margin-bottom: -10px;"> &#9947;</div>
            @else
            &nbsp;
            @endif
            <h4>{{$gameParticipants[2]['name']}}&nbsp;(&nbsp;{{$gameParticipants[2]['totalScore']}}&nbsp;)</h4>
        </div>
        @foreach($gameParticipants[2]['hands'] as $hand)
        <div class="row gx-0 align-items-top text-center">
            <div class="col-3">
                @if($gameParticipants[0]['isTurn'] && ($skill == 'bossFeWrqtGherak' || ($skill == 'kaabDayer' && in_array($gameParticipants[2]['participant']->user_id, json_decode($gameParticipants[0]['participant']->kaab_dayer, true)))))
                <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary border-0" style="padding: 0;">&nbsp;اكشف&nbsp;</a>
                @elseif($gameParticipants[0]['isTurn'] && $skill == 'KhodWHat')
                <a href="{{ route('bdel', [$hand->id]) }}" type="button" class="btn btn-outline-success border-0" style="padding: 0;">&nbsp;بـــدل&nbsp;</a>
                @endif
            </div>
            <div class="col-8">
                @if(count($gameParticipants[2]['hands']) == $hand->index)
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

        <div class="row text-center mt-3 mb-1">
            @if($gameParticipants[1]['isTurn'])
            <div style="font-size: 30px; color: red; margin-bottom: -10px;"> &#9947;</div>
            @else
            &nbsp;
            @endif
            <h4>{{$gameParticipants[1]['name']}}&nbsp;(&nbsp;{{$gameParticipants[1]['totalScore']}}&nbsp;)</h4>
        </div>
        @foreach($gameParticipants[1]['hands'] as $hand)
        <div class="row gx-0 align-items-top text-center">
            <div class="col-3">
                @if($gameParticipants[0]['isTurn'] && ($skill == 'bossFeWrqtGherak' || ($skill == 'kaabDayer' && in_array($gameParticipants[1]['participant']->user_id, json_decode($gameParticipants[0]['participant']->kaab_dayer, true)))))
                <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary border-0" style="padding: 0;">&nbsp;اكشف&nbsp;</a>
                @elseif($gameParticipants[0]['isTurn'] && $skill == 'KhodWHat')
                <a href="{{ route('bdel', [$hand->id]) }}" type="button" class="btn btn-outline-success border-0" style="padding: 0;">&nbsp;بـــدل&nbsp;</a>
                @endif
            </div>
            <div class="col-8">
                @if(count($gameParticipants[1]['hands']) == $hand->index)
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