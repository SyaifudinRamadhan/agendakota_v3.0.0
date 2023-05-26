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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    @yield('head.dependencies')
</head>

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

            .menu a {
                /* text-decoration: none; */
            }

        </style>


        <div class="menu col-md">
            <div class="wrap">
                <h4>GENERAL</h4>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-star teks-primer mr-4 col-md-2 row"></i>Dashboard
                </li>
            </a>
            <a href="{{ route('admin.organizer') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.organizer' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-building teks-primer mr-4 col-md-2 row"></i>Event Organizer
                </li>
            </a>

            {{-- Pembaruan --}}
            <h4 class="pointer">MANAGE PAGE</h4>
            <a href="{{ route('admin.page') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.page' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-file teks-primer mr-4 col-md-2 row"></i>Page Statis
                </li>
            </a>
            <a href="{{ route('admin.home.banner') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.home.banner' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-file teks-primer mr-4 col-md-2 row"></i>Home Banner
                </li>
            </a>
            {{-- ----------------------- --}}
            {{-- <div>
                <div>
                    <a onclick="expandMenu(this)" class="pointer font-16">
                        <li class=>
                            <i class="fa fa-lg fa-file teks-primer mr-4 col-md-2 row"></i>Manage Page
                        </li>
                    </a>
                </div>
                <ul>
                    <a href="{{ route('admin.faq') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.faq' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-question-circle teks-primer mr-4 col-md-2 row"></i>F.A.Q
                        </li>
                    </a>
                    <a href="{{ route('admin.page') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.page' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-file teks-primer mr-4 col-md-2 row"></i>Page Statis
                        </li>
                    </a>
                </ul>
            </div>            --}}

            {{-- Pembaruan --}}
            <h4 class="pointer">MANAGE USER</h4>
            <a href="{{ route('admin.user') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.user' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-users teks-primer mr-4 col-md-2 row"></i>User
                </li>
            </a>
            <a href="{{ route('admin.karyawan') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.karyawan' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-user-tie teks-primer mr-4 col-md-2 row"></i>Admin
                </li>
            </a>
            <a href="{{ route('admin.user.organizationType') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.user.organizationType' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-user-tie teks-primer mr-4 col-md-2 row"></i>Tipe Organisasi
                </li>
            </a>
            {{-- ----------------------------- --}}

            {{-- <div>
                <div>
                    <a onclick="expandMenu(this)" class="pointer font-16">
                        <li class="{{ Route::currentRouteName() == 'admin.*' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-user teks-primer mr-4 col-md-2 row"></i>Manage User
                        </li>
                    </a>
                </div>
                <ul>
                    <a href="{{ route('admin.user') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.user' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-users teks-primer mr-4 col-md-2 row"></i>User
                        </li>
                    </a>
                    <a href="{{ route('admin.karyawan') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.karyawan' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-user-tie teks-primer mr-4 col-md-2 row"></i>Admin
                        </li>
                    </a>
                    <a href="{{ route('admin.finance') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.finance' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-user-secret teks-primer mr-4 col-md-2 row"></i>Finance
                        </li>
                    </a>
                    <a href="{{ route('admin.manager') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.manager' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-user-cog teks-primer mr-4 col-md-2 row"></i>Manager
                        </li>
                    </a>
                    <a href="{{ route('admin.author') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.author' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-user-edit teks-primer mr-4 col-md-2 row"></i>Author
                        </li>
                    </a>
                </ul>
            </div> --}}

            {{-- Pembaruan --}}
            <h4 class="pointer">MANAGE EVENT</h4>
            <a href="{{ route('admin.category') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.category' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-tags teks-primer mr-4 col-md-2 row"></i>Event Category
                </li>
            </a>
            <a href="{{ route('admin.city') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.city' ? 'active' : '' }}">
                    <i class="fa fa-lg bi bi-building teks-primer mr-4 col-md-2 row"></i>Event City
                </li>
            </a>
            <a href="{{ route('admin.event') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.event' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-calendar teks-primer mr-4 col-md-2 row"></i>Events
                </li>
            </a>
            {{-- ------------------- --}}

            {{-- <div>
                <div>
                    <a onclick="expandMenu(this)" class="pointer font-16">
                        <li class=>
                            <i class="fa fa-lg fa-calendar-check teks-primer mr-4 col-md-2 row"></i>Manage Event
                        </li>
                    </a>
                </div>
                <ul>
                    <a href="{{ route('admin.event') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.event' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-calendar teks-primer mr-4 col-md-2 row"></i>Event
                        </li>
                    </a>
                    <a href="{{ route('admin.category') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.category' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-tags teks-primer mr-4 col-md-2 row"></i>Event Category
                        </li>
                    </a>
                </ul>
            </div> --}}

            {{-- Pembaruan --}}
            <h4 class="pointer">MANAGE PACKAGES</h4>
            <a href="{{ route('admin.packages') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.packages' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-poll teks-primer mr-4 col-md-2 row"></i>Penjualan Paket
                </li>
            </a>
            <a href="{{ route('admin.package.user-mng') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.package.user-mng' ? 'active' : '' }} 
                {{ Route::currentRouteName() == 'admin.package.user-detail' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-gift teks-primer mr-4 col-md-2 row"></i>Paket User
                </li>
            </a>
            <a href="{{ route('admin.package.all') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.package.all' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-gear teks-primer mr-4 col-md-2 row"></i>Kelola Paket
                </li>
            </a>

            <h4 class="pointer">MANAGE FINANCE</h4>
            <a href="{{ route('admin.finance-report') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.finance-report' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-poll teks-primer mr-4 col-md-2 row"></i>Laporan Keuangan
                </li>
            </a>
            <a href="{{ route('admin.finance-report.withdrawals') }}" class="font-16">
                <li class="ml-4 {{ Route::currentRouteName() == 'admin.finance-report.withdrawals' ? 'active' : '' }} 
                {{ Route::currentRouteName() == 'admin.finance-report.withdraw-user' ? 'active' : '' }}
                {{ Route::currentRouteName() == 'admin.finance-report.withdraw-userid' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-hand-holding teks-primer mr-4 col-md-2 row"></i>Penarikan
                </li>
            </a>
            {{-- ------------------------------- --}}

            {{-- <div>
                <div>
                    <a onclick="expandMenu(this)" class="pointer font-16">
                        <li class=>
                            <i class="fa fa-lg fa-money-bill teks-primer mr-4 col-md-2 row"></i>Manage Finance
                        </li>
                    </a>
                </div>
                <ul>
                    <a href="{{ route('admin.event') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.event' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-poll teks-primer mr-4 col-md-2 row"></i>Laporan Keuangan
                        </li>
                    </a>
                    <a href="{{ route('admin.category') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.category' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-hand-holding teks-primer mr-4 col-md-2 row"></i>Penarikan
                        </li>
                    </a>
                </ul>
            </div> 
            <div>
                <div>
                    <a onclick="expandMenu(this)" class="pointer font-16">
                        <li class=>
                            <i class="fa fa-lg fa-pen teks-primer mr-4 col-md-2 row"></i>Article
                        </li>
                    </a>
                </div>
                <ul>
                    <a href="{{ route('admin.event') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.event' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-edit teks-primer mr-4 col-md-2 row"></i>Post New Article
                        </li>
                    </a>
                    <a href="{{ route('admin.category') }}" class="font-16">
                        <li class="ml-4 {{ Route::currentRouteName() == 'admin.category' ? 'active' : '' }}">
                            <i class="fa fa-lg fa-calendar-plus teks-primer mr-4 col-md-2 row"></i>Add Category
                        </li>
                    </a>
                </ul>
            </div> --}}
            {{-- <a href="{{ route('user.profile') }}" class="font-16">
                <li class="{{ Route::currentRouteName() == 'user.profile' ? 'active' : '' }}">
                    <i class="fa fa-lg fa-user-circle teks-primer mr-4 col-md-2 row"></i>Profile
                </li>
            </a> --}}
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
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/29.1.0/classic/ckeditor.js"></script>
    <script>
        const expandMenu = btn => {
            console.log("clicked");
            let toExpand = btn.parentNode.parentNode.children[1];
            let style = getComputedStyle(toExpand);
            if (style.display == "block") {
                toExpand.style.display = "none";
            } else {
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
            $('#finance-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('data.finance') !!}',
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
            $('#manager-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('data.manager') !!}',
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
            $('#author-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('data.author') !!}',
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
                        data: 'organizer.name',
                        name: 'organizer'
                    },
                    {
                        data: 'organizer.email',
                        name: 'email'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

        ClassicEditor
            .create(document.querySelector('.ckeditor'))
            .catch(error => {
                console.error(error);
            });

        AOS.init();
    </script>

    @yield('javascript')
    {{-- Sweet alert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">

</body>

</html>
