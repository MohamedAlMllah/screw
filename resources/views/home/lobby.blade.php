<div class="text-end mb-3">
    <a class="btn btn-outline-primary mt-3" href="{{ route('games.create') }}">
        New Game
    </a>
</div>

@if(count($games))
<div class="container text-center">
    <div class="row row-cols-lg-3 row-cols-sm-2">
        @foreach ($games as $game)
        <div class="mt-4">
            <div class="card h-100">
                <h5 class="card-header"><b>{{$game['admin']->name}}</b></h5>
                <div class="card-body">
                    <p class="card-text">Losing Score : {{$game['game']->lose_score}}</p>
                    <p class="card-text">Number Of Shuffles : {{$game['game']->number_of_shuffles}}</p>
                    <p class="card-text">Number Of Players : {{$game['participantsCount']}} / {{$game['game']->number_of_players}}</p>

                </div>
                <div class="card-footer bg-white d-flex">
                    <a href="{{ route('join', [$game['game']->id]) }}" onclick="$('#formJoin').attr('action', this.href)" type="button" class="card-link" data-bs-toggle="modal" data-bs-target="#joinModal">Join</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@else
<h5 class="mt-3"> No Games to show for now!</h5>
<meta http-equiv="refresh" content="5">
@endif