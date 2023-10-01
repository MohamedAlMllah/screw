<div class="text-end mb-3">
    @if($game->admin_id == $user->id)
    <a href="{{ route('games.destroy', [$game->id]) }}" onclick="$('#formDelete').attr('action', this.href)" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">End game for all</a>
    @else
    <a href="{{ route('leave', [$game->id]) }}" onclick="$('#formLeave').attr('action', this.href)" type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#leaveModal">Leave Game</a>
    @endif
</div>

@if($game->scores()->count() < 2) <h1>waiting for other players to join {{$game->scores()->count()}} / 2</h1>
    <meta http-equiv="refresh" content="5">
    @else
    <div class="row text-center mb-1">
        <h4>{{$game->getPlayerInOrder(1,$user)->name}}&nbsp;(&nbsp;{{$game->getPlayerInOrder(1,$user)->score->score}}&nbsp;)</h4>
    </div>
    <div class="row mb-5">
        <div class="col-2">&nbsp;</div>
        @foreach($game->getPlayerInOrder(1,$user)->hands->sortBy('index') as $hand)
        <div class="col-2">
            <div class="card text-center">
                {{$hand->index}}
                <img src="{{asset($hand->card->image)}}" class="card-img-top img-fluid" style="height:50%" alt="...">
                @if($user->score->turn)
                <a href="#" class="btn btn-outline-info">نفذ</a>
                @endif
            </div>
        </div>
        @endforeach

    </div>

    <div class="row mb-5 align-items-center">
        <div class="col-2 offset-2 text-center">
            &nbsp;
            @if($user->score->turn && !$game->hasScrewPlayer())
            <a href="{{ route('screw', [$user->score->id]) }}" class="btn btn-outline-danger fs-6">سكرو</a>
            @endif
        </div>
        <div class="col-2">
            <div class="card text-center">
                {{$elkomaElmqlopaCount}} عدد الكروت
                <img src="{{asset('images/cards/back.png')}}" class="card-img-top img-fluid" alt="...">
                @if($user->score->turn)
                <a href="{{ route('ekshif', [$game->getAwlElkomaElmqlopa()->id]) }}" class="btn btn-outline-primary fs-6">اكشف</a>
                @endif
            </div>
        </div>
        <div class="col-2">
            <div class="card text-center">
                {{$elkomaElmkshofaCount}} عدد الكروت
                <img src="{{asset($awlElkomaElmkshofa->card->image)}}" class="card-img-top img-fluid" alt="...">
                @if($user->score->turn)
                <a href="{{ route('bdel', [$awlElkomaElmkshofa->id]) }}" onclick="$('#formBdel').attr('action', this.href)" type="button" class="btn btn-outline-success fs-6" data-bs-toggle="modal" data-bs-target="#bdelModal">بـــدل</a>
                @endif
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-2">&nbsp;</div>
        @php($key = 1)
        @foreach($user->hands->sortBy('index') as $hand)
        <div class="col-2">
            <div class="card text-center">
                {{$hand->index}}
                <img src="{{asset($hand->card->image)}}" class="card-img-top img-fluid" alt="...">
                @if($user->score->turn)
                <a href="{{ route('bsra', [$hand->id]) }}" class="btn btn-outline-warning">بصرة</a>
                @endif
            </div>
        </div>
        @endforeach

    </div>
    @endif
    @if(!$user->score->turn)
    <meta http-equiv="refresh" content="5">
    @endif