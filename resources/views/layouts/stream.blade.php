<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Agendakota</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fa/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('js/select2.min.css') }}">

    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{ asset('images/icon-ak.png') }}">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https:////cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> 

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    @yield('head.dependencies')
</head>

<style>
    body {
        overflow: hidden
    }

    .thumbnail {
        width: 4vw;
        height: 4vw;
        border-radius: 4vw;
        margin-top: 3vh;
        margin-bottom: 3vh;
    }

    .vertical-menu {
        /* width: 10vw; */
        height: 100vh;
        font-family: 'Inter';
        border-right: solid 1px #EBEBEB;
        font-size: 15px;
    }

    .vertical-menu a {
        color: black;
        display: block;
        padding-top: 3vh;
        padding-bottom: 3vh;
        text-decoration: none;

    }

    .vertical-menu a:hover {
        background-color: #eb597b9a;
        /* Dark grey background on mouse-over */
    }

    .vertical-menu a.active {
        background-color: #e5214f; /*#eb597b*/
        /* Add a green color to the "active/current" link */
        color: white;
    }

    .icon-menu {
        font-size: 22px;
    }

    .contents {
        padding-top: 10vh;
        /* width: 60vw; */
        height: 100vh;
        border-right: solid 1px #EBEBEB;
        overflow-y: auto;
    }

    .content2 {
        /* width: 30vw; */
        height: 100vh;
        float: left;
        border-right: solid 1px #EBEBEB;
        /* background-color: #e5214f; /*#eb597b*/ */
        /* overflow-y: scroll;
            overflow-x: hidden; */

    }

    .content2 .header-chat {
        padding-top: 20vh;
        border-bottom: 1px solid rgb(226, 226, 226);
    }

    .content2 .messages-kanan-detil {
        height: 100vh;
        padding-bottom: 40vh;        
        background-color: #fff;
        overflow-y:auto;        
    }

    .content2 .messages-kanan-detil .img-chat {
        height: 50px;
        width: 50px;
    }

    .content2 .messages-kanan-kirim {
        position: sticky;
        bottom: 0;
    }

</style>
@yield('head.style')

<body>

    <header>
        <a href="{{ route('user.homePage') }}">
            <img src="{{ asset('images/logo.png') }}" class="logo">
        </a>
        <nav class="nav-atas" id="nav-atas">
            <a>
                <li><a href="{{ route('admin.logout') }}" style="text-decoration: none;"
                        class="btn bg-primer text-white"><i class="fa fa-sign-out"></i> Logout</a></li>
            </a>
        </nav>
    </header>
    <div class="row">

        <div class="vertical-menu pt-3 mt-5 text-center justify-content-center bg-light col-md-1 col">
            <div class="mx-auto text-center d-block">
                @if ($organization->logo == 'default')
                    <img class="thumbnail" src="{{ asset('images/profile-user.png') }}">
                @else
                    <img class="thumbnail" src="{{ asset('storage/organizer_logo/' . $organization->logo) }}">
                @endif
            </div>
            <a href="{{route('stream.index', $event->slug)}}">
                <h3><i class="fa fa-star icon-menu teks-primer d-block mx-auto"></i></h3> Event
            </a>
            <a href="{{route('stream.stage', $event->slug)}}">
                <h3><i class="fa fa-video icon-menu teks-primer d-block mx-auto"></i></h3> Stage
            </a>
        </div>

        <div class="contents col-md-7 col">
            @yield('content')
        </div>

        <div class="content2 col-md-4 col">
            @yield('content2')
        </div>

    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    
    

    <script src="{{ asset('js/base.js') }}"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script> --}}
    <script src="https://kit.fontawesome.com/466fd0939d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/29.1.0/classic/ckeditor.js"></script>

    <script>
    </script>

    @yield('javascript')

</body>

</html>
