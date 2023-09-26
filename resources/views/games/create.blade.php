@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">New Game</div>

                <div class="card-body">
                    <form action="{{ route('games.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h5 class="mb-1">Losing Score</h5>
                        <div class="form-group">
                            <input type="number" min="0" step="10" class="form-control @error('score') is-invalid @enderror" placeholder="Enter Losing Score" value="{{old('score') ?? '0'}}" name="score">
                        </div>
                        @error('score')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <h5 class="mb-1 mt-3">Password</h5>
                        <div class="form-group">
                            <input type="text" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" value="{{old('password')}}" name="password">
                        </div>
                        @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-outline-success" style="width: 40%">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection