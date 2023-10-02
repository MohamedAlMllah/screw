@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">

                <div class="card-body">
                    @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if($user->participant)
                    @include('home.playingTable')
                    @else
                    @include('home.lobby')
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.modals.delete')
@include('layouts.modals.join')
@include('layouts.modals.leave')
@include('layouts.modals.bdel')
@endsection