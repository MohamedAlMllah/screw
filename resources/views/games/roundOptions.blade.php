@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Round Options</div>

                <div class="card-body">
                    <form action="{{ route('setRoundOptions', [$game->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h5 class="mb-1 mt-3">Starting Covered Cards</h5>
                        <div class="form-group">
                            <select class="form-select" name="startingCoveredCards">
                                <option selected value="2">Normal (2)</option>
                                <option value="4">All Bliend</option>
                            </select>
                        </div>
                        <h5 class="mb-1 mt-3">Multiple Score</h5>
                        <div class="form-group">
                            <select class="form-select" name="multipleScore">
                                <option selected value="1">Normal (x1)</option>
                                <option value="2">x2</option>
                                <option value="3">x3</option>
                                <option value="4">x4</option>
                            </select>
                        </div>

                        <div class="form-group text-center mt-5">
                            <button type="submit" class="btn btn-outline-success" style="width: 40%">Start Round</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection