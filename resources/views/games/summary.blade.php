@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card mt-4">
                <div class="card-body">
                    @if(!$numberOfScores)
                    <h1>No Scores yet</h1>
                    @else
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th scope="col">الجولة</th>
                                @foreach($playersNames as $playerName)
                                <th scope="col">{{$playerName}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @for($i=0; $i<$numberOfRounds; $i++) <tr>
                                <th scope="row">{{$i+1}}</th>
                                @for($j=0; $j<$numberOfPlayers; $j++) <td>{{$scores[$j][$i]->value}}</td>
                                    @endfor
                                    </tr>
                                    @endfor
                                    <tr>
                                        <th scope="row">المجموع</th>
                                        @for($j=0; $j<$numberOfPlayers; $j++) <th scope="col">{{$totalScores[$j]}}</th>
                                            @endfor
                                    </tr>

                        </tbody>
                    </table>
                    @endif

                    @if($gameIsFinished)
                    <div class="text-center">
                        <a href="{{ route('leave', [$game->id]) }}" onclick="$('#formLeave').attr('action', this.href)" type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#leaveModal">Leave Game</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.modals.leave')
@endsection