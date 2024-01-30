@extends('layouts.app')

@section('content')
<div id="h">

    <style>
        #h {
            background-image: url('{{asset('images/landingPageBG.jpg')}}');
            background-size: auto;
            background-position: center;
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }
    </style>

    <header class="masthead d-flex aligns-items-center justify-content-center">
        <div style="padding-top: 60vh; padding-bottom: 19vh;" class="container  text-center aligns-items-center">
            @if (Route::has('login'))
            @auth
            <a href="{{ route('home') }}" class="btn btn-primary col-md-3 col-5">
                <h3> Dashboard </h3>
            </a>
            @else
            <a href="{{ route('login') }}" class="btn btn-success col-md-3 col-5 pr-3">
                <h3> Log in </h3>
            </a>
            &nbsp;&nbsp;&nbsp;&nbsp;
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary col-md-3 col-5">
                <h3> Register </h3>
            </a>
            @endif
            @endauth
            @endif
        </div>
    </header>
</div>
@endsection