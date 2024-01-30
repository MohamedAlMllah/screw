@if($numberOfPlayers % 2 == 0)
<div class="row text-center mb-1">
    @if($gameParticipants[$numberOfPlayers/2]['isTurn'])
    <div style="font-size: 30px; color: red; margin-bottom: -10px;"> &#9947;</div>
    @endif
    <h4>{{$gameParticipants[$numberOfPlayers/2]['name']}}&nbsp;(&nbsp;{{$gameParticipants[$numberOfPlayers/2]['totalScore']}}&nbsp;)</h4>
</div>
<div class="row mb-5 gx-0">
    <div class="col-5">&nbsp;</div>
    <div class="col-7 row gx-0">
        @foreach($gameParticipants[$numberOfPlayers/2]['hands'] as $hand)
        @if(count($gameParticipants[$numberOfPlayers/2]['hands']) == $hand->index || $hand->index % 5 == 0)
        <div class="col-4">
            @else
            <div class="col-2">
                @endif
                <div class="text-center">
                    {{$hand->index}}
                    @if(count($gameParticipants[$numberOfPlayers/2]['hands']) == $hand->index || $hand->index % 5 == 0)
                    <img src="{{asset('images/cards/back.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                    @else
                    <img src="{{asset('images/cards/backHalf.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                    @endif
                    @if($gameParticipants[0]['isTurn'] && ($skill == 'bossFeWrqtGherak' || ($skill == 'kaabDayer' && in_array($gameParticipants[$numberOfPlayers/2]['participant']->user_id, json_decode($gameParticipants[0]['participant']->kaab_dayer, true)))))
                    <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary border-0 mt-1">اكشف</a>
                    @elseif($gameParticipants[0]['isTurn'] && $skill == 'KhodWHat')
                    <a href="{{ route('bdel', [$hand->id]) }}" type="button" class="btn btn-outline-success border-0 mt-1">بـــدل</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif