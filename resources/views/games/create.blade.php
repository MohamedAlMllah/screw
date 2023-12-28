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
                            <input type="number" autofocus min="0" step="1" class="form-control @error('score') is-invalid @enderror" placeholder="Enter Losing Score" value="{{old('score') ?? '100'}}" name="score">
                        </div>
                        @error('score')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <h5 class="mb-1 mt-3">Number Of Players</h5>
                        <div class="form-group">
                            <select class="form-select" name="numberOfPlayers">
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option selected value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                        <h5 class="mb-1 mt-3">Number Of Shuffles</h5>
                        <div class="form-group">
                            <select class="form-select" name="numberOfShuffles">
                                <option selected value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                        <h5 class="mb-1 mt-3">Password</h5>
                        <div class="form-group">
                            <input type="text" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" value="{{old('password')}}" name="password">
                        </div>
                        @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="form-group text-center mt-5">
                            <button type="submit" class="btn btn-outline-success" style="width: 40%">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection