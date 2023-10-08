@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3>عايز تبدله مع انهي كارت ؟</h3>
                    <div class="row">
                        <div class="col-2">&nbsp;</div>
                        @foreach($user->hands->sortBy('index') as $hand)
                        <div class="col-2">
                            <div class="card text-center">
                                {{$hand->index}}
                                <a href="{{ route('bdelWith', [$bdelWithHand->id, $hand->index]) }}">
                                    <img src="{{asset('images/cards/back.png')}}" class="card-img-top img-fluid" alt="...">
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection