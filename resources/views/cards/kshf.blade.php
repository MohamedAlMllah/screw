@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">

                <div class="card-body">
                    <div class="col-4 offset-4">
                        <div class="card">
                            <img src="{{asset($awlElkomaElmqlopa->card->image)}}" class="card-img-top img-fluid" alt="...">
                            <div class="card-body text-center">

                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                    <a href="{{ route('bdel', [$awlElkomaElmqlopa->id]) }}" onclick="$('#formBdel').attr('action', this.href)" type="button" class="btn btn-outline-primary fs-4" data-bs-toggle="modal" data-bs-target="#bdelModal">بـــدل</a>
                                    <a href="{{ route('ermy', [$awlElkomaElmqlopa->id]) }}" class="btn btn-outline-secondary fs-4">ارمي</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.modals.bdel')
@endsection