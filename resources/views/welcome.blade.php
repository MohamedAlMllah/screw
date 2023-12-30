<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Screw</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <style>
        body {
            background-image: url('{{asset('images/landingPageBG.JPG')}}');
            background-size: auto;
            background-position: center;
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }
    </style>
</head>

<body>
    <div class="ml-4 mt-4">
        <img src="{{asset('images/Logo.png')}}" style="width: 10vh;" alt="wrong">

        <h4 class="mt-3">Screw</h4>
    </div>
    <header class="masthead d-flex aligns-items-center justify-content-center">
        <div style="padding-top: 50vh; padding-bottom: 20vh;" class="container  text-center aligns-items-center">
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



</body>

</html>