@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
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
                </div>
            </div>
        </div>
    </div>
</div>

@endsection