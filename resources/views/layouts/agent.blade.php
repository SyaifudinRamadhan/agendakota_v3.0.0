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
    <link rel="stylesheet" href="{{ asset('js/select2.min.css') }}">

    <!-- Fevicon -->
    <link rel="shortcut icon" href="{{ asset('images/icon-ak.png') }}">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;600;700&display=swap" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="https:////cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    @yield('head.dependencies')
</head>

<body>

    <header>
        <a href="{{ route('user.homePage') }}">
            <img src="{{ asset('images/logo.png') }}" class="logo">
        </a>
        <nav class="nav-atas" id="nav-atas">
            <a>
                <li><a href="{{ route('user.logout') }}" style="text-decoration: none;"
                        class="btn bg-primer text-white"><i class="fa fa-sign-out"></i> Logout</a></li>
            </a>
        </nav>
    </header>
    <nav class="left nav-atas">
        <style>
            nav.left .menu li {
                line-height: 35px;
                font-size: 16px;
                padding-left: 5%;

            }

            nav.left .menu h4 {
                line-height: 30px;
                padding-left: 5%;
            }

            nav.left .menu i {
                margin-right: 10%;
            }

        </style>


        <div class="menu col-md">
            <div class="wrap">
                <h4>GENERAL</h4>
            </div>
            <a href="{{ route('agent.dashboard') }}" class="font-16">
                <li class="{{ Route::currentRouteName() == 'agent.dashboard' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-user teks-primer mr-4 col-md-2 row"></i>Dashboard
                </li>
            </a>
            <a href="{{ route('agent.handout') }}" class="font-16">
                <li class="{{ Route::currentRouteName() == 'agent.handout' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-file teks-primer mr-4 col-md-2 row"></i> Handout
                </li>
            </a>
            <a href="{{ route('agent.product') }}" class="font-16">
                <li class="{{ Route::currentRouteName() == 'agent.product' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-shopping-bag teks-primer mr-4 col-md-2 row"></i> Produk
                </li>
            </a>
        </div>

        @yield('navigation')
    </nav>

    <div class="content container-cadangan">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/base.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/466fd0939d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script>
        const expandMenu = btn => {
            console.log("clicked");
            let toExpand = btn.parentNode.parentNode.children[1];
            let style = getComputedStyle(toExpand);
            if (style.display == "block") {
                btn.classList.add('fa-plus');
                btn.classList.remove('fa-minus');
                toExpand.style.display = "none";
            } else {
                btn.classList.remove('fa-plus');
                btn.classList.add('fa-minus');
                toExpand.style.display = "block";
            }
        }


        const chooseFile = input => {
            let file = input.files[0];
            let reader = new FileReader();
            let preview = select("#logoPreview");
            reader.readAsDataURL(file);

            reader.addEventListener("load", function() {
                select("#inputLogoArea").classList.add('d-none');
                select("#previewLogoArea").classList.remove('d-none');
                preview.setAttribute('src', reader.result);
            });
        }
        const removePreview = () => {
            select("#inputLogoArea").classList.remove('d-none');
            select("#previewLogoArea").classList.add('d-none');
        }

        function myFunction() {
            var x = document.getElementById("myTopnav");
            if (x.className === "topnav") {
                x.className += " responsive";
            } else {
                x.className = "topnav";
            }
        }


        $(function() {
            $('#karyawan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('data.karyawan') !!}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        $(function() {
            $('#user-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('data.user') !!}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        $(function() {
            $('#organizer-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('data.organizer') !!}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        $(function() {
            $('#event-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('data.event') !!}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>

    @yield('javascript')

</body>

</html>
