<div class="row text-center mb-1">
    @if($user->participant->is_turn)
    <div style="font-size: 30px; color: red; margin-bottom: -10px;"> &#9947;</div>
    @endif
    <h4>{{$user->name}}&nbsp;(&nbsp;{{$user->totalScore($game)}}&nbsp;)</h4>
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
        @elseif($playersNotViewedTwoCards->count())
        <div class="col-11 card">
            <div class="card-header text-center">
                <b>Waiting for</b>
            </div>
            <ul class="list-group">
                @foreach ($playersNotViewedTwoCards as $player)
                <li class="list-group-item">
                    {{$player->user->name}}
                </li>
                @endforeach
            </ul>
        </div>
        @else
        &nbsp;
        @endif
    </div>
    <div class="col-7 row gx-0">
        @foreach($user->hands->sortBy('index') as $hand)
        @if($user->hands->count() == $hand->index || $hand->index == 5)
        <div class="col-4">
            @else
            <div class="col-2">
                @endif
                <div class="text-center">
                    {{$hand->index}}
                    @if($user->hands->count() == $hand->index || $hand->index == 5)
                    <img src="{{asset('images/cards/back.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                    @else
                    <img src="{{asset('images/cards/backHalf.png')}}" class="card-img-top img-fluid rounded-0" alt="...">
                    @endif
                    @if($user->participant->is_turn && $skill == 'normal' && !$playersNotViewedTwoCards->count())
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
    </div>