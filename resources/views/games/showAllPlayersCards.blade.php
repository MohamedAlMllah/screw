@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">Players cards</div>

                <div class="card-body">
                    @foreach($participants as $participant)
                    <div class="row gx-1">
                        <div class="col-2 text-center"><b>{{$participant->user->name}}</b></div>
                        @foreach($participant->user->hands as $hand)
                        @if($hand->index % 5 == 1 && $hand->index != 1)
                        <div class="col-2 offset-2">
                            @else
                            <div class="col-2">
                                @endif
                                <img src="{{asset($hand->card->image)}}" class="card-img-top img-fluid" alt="card">
                            </div>
                            @endforeach
                        </div><br>
                        @endforeach
                        @if($isReady)
                        <h1>
                            Waiting for other players.
                        </h1>
                        @else
                        <div class="row mt-3">
                            <div class="col text-center">
                                <a href="{{ route('endRound', [$game->id]) }}" class="btn btn-outline-success fs-4 col-4">تمام</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    <meta http-equiv="refresh" content="5">