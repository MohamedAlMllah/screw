@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card mt-4">

                <div class="card-body">
                    @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif
                    @if($skill == 'showTwoCards')
                    <div class="row">
                        <div class="col-4 offset-2">
                            <div class="card text-center">
                                1
                                <img src="{{asset($card1->image)}}" class="card-img-top img-fluid" alt="...">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card text-center">
                                2
                                <img src="{{asset($card2->image)}}" class="card-img-top img-fluid" alt="...">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col text-center">
                            <a href="{{ route('endSkill', [$hand->id]) }}" class="btn btn-outline-success fs-4 col-4">تمام</a>
                        </div>
                    </div>
                    @elseif($skill == 'bossFeWrqtak' || $skill == 'bossFeWrqtGherak' || $skill == 'kaabDayer')
                    <div class="col-4 offset-4">
                        <div class="card text-center">
                            {{$hand->index}}
                            <img src="{{asset($card->image)}}" class="card-img-top img-fluid" alt="...">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col text-center">
                            <a href="{{ route('endSkill', [$hand->id]) }}" class="btn btn-outline-success fs-4 col-4">تمام</a>
                        </div>
                    </div>
                    @else
                    <div class="col-4 offset-4">
                        <div class="card">
                            <img src="{{asset($card->image)}}" class="card-img-top img-fluid" alt="...">
                            <div class="card-body text-center">
                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                    <a href="{{ route('bdel', [$hand->id]) }}" type="button" class="btn btn-outline-primary fs-4">بـــدل</a>
                                    <a href="{{ route('ermy', [$hand->id]) }}" class="btn btn-outline-secondary fs-4">ارمي</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection