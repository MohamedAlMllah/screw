<div class="row text-center mb-1">
    @if($participant->is_turn)
    <div style="font-size: 30px; color: red; margin-bottom: -10px;"> &#9947;</div>
    @endif
    <h4>{{$gameParticipants[0]['name']}}&nbsp;(&nbsp;{{$gameParticipants[0]['totalScore']}}&nbsp;)</h4>
</div>
<div class="row gx-0">
    <div class="col-5">
        @if ($announcements->count())
        <div class="col-11 card">
            <div class="card-header text-center">
                <b>Captain Ayman ElKashif</b>
            </div>
            <ul class="list-group">
                @foreach ($announcements as $announcement)
                <li class="list-group-item">
                    {!! $announcement->text !!}
                </li>
                @endforeach
            </ul>
        </div>
        @elseif($playersNotViewedTwoCardsCount)
        <div class="col-11 card">
            <div class="card-header text-center">
                <b>Waiting for</b>
            </div>
            <ul class="list-group">
                @foreach ($gameParticipants as $player)
                @if( $player['skill'] == 'showTwoCards')
                <li class="list-group-item">
                    {{$player['name']}}
                </li>
                @endif
                @endforeach
            </ul>
        </div>
        @else
        &nbsp;
        @endif
    </div>
    <div class="col-7 row gx-0">
        @foreach($gameParticipants[0]['hands'] as $hand)
        @if(count($gameParticipants[0]['hands']) == $hand->index || $hand->index % 5 == 0)
        <div class="col-4">
            @else
            <div class="col-2">
                @endif
                <div class="text-center">
                    {{$hand->index}}
                    @if(count($gameParticipants[0]['hands']) == $hand->index || $hand->index % 5 == 0)
                    <img src="{{asset('images/cards/back.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                    @else
                    <img src="{{asset('images/cards/backHalf.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                    @endif
                    @if(($gameParticipants[0]['isTurn'] && $skill == 'normal' && !$playersNotViewedTwoCardsCount) || ($gameParticipants[0]['isTurn'] && $skill == 'bsra'))
                    <a href="{{ route('bsra', [$hand->id]) }}" class="btn btn-outline-warning mt-1">بصرة</a>
                    @elseif($gameParticipants[0]['isTurn'] && ($skill == 'bossFeWrqtak' || ($skill == 'kaabDayer' && in_array($gameParticipants[0]['participant']->user_id, json_decode($gameParticipants[0]['participant']->kaab_dayer, true)))))
                    <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary mt-1">اكشف</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>