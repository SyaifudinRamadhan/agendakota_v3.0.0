<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fa/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/466fd0939d.js" crossorigin="anonymous"></script>
    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{ asset('images/icon-ak.png') }}">
    @yield('head.dependencies')
</head>

<body>

    <div class="container">      
        <div class="wrap super">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/base.js') }}"></script>
    @yield('javascript')

</body>

</html>
