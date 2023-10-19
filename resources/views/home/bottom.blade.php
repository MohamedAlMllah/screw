<div class="row gx-2 gx-lg-3">
    <div class="col-2">&nbsp;</div>
    @foreach($user->hands->sortBy('index') as $hand)
    <div class="col-2">
        <div class="text-center">
            {{$hand->index}}
            <img src="{{asset($hand->card->image)}}" class="card-img-top img-fluid" alt="...">
            @if($user->participant->is_turn && $skill == 'normal')
            <a href="{{ route('bsra', [$hand->id]) }}" class="btn btn-outline-warning mt-1">بصرة</a>
            @elseif($user->participant->is_turn && $skill == 'bossFeWrqtak')
            <a href="{{ route('ekshif', [$hand->id]) }}" class="btn btn-outline-primary mt-1">اكشف</a>
            @elseif($user->participant->is_turn && $skill == 'bsra')
            <a href="{{ route('bsra', [$hand->id]) }}" class="btn btn-outline-warning mt-1">بصرة</a>
            @endif
        </div>
    </div>
    @endforeach
</div>