<!doctype html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>TomSolution CMS</title>


    <link href="{{asset('_admin/css/vendors/jquery-ui.css')}}" rel="stylesheet">
    <link href="{{asset('_admin/css/vendors/jquery-ui.theme.css')}}" rel="stylesheet">
    <link href="{{asset('_admin/css/vendors/jquery-ui.structure.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('_admin/css/fontawesome.min.css')}}">
    <link href="{{asset('_admin/css/vendors/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('_admin/css/vendors/dashboard.css')}}" rel="stylesheet">
    <link href="{{asset('_admin/css/main.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">


    @if(Auth::user()->theme === 'dark')
        <link rel="stylesheet" href="{{asset('css/admin-layout-dark.css')}}">
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        const baseUrl = '{{url()->to('/')}}/admin/';
        const csrfToken = '{{csrf_token()}}';
        const API_URL = '{{url()->to('api')}}/';
    </script>

    @stack('script.head.bottom')

</head>
<body>

<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="{{route('admin.dashboard')}}">TomSolution CMS</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse"
            data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="w-100"></div>
{{--    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">--}}
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            @include('admin._helpers.langs')
        </li>
    </ul>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="{{route('admin.user.show')}}">
{{--                <span data-feather="user"></span>--}}
                <i class="fas fa-user-circle"> </i>
                {{ Auth::user()->name }}
            </a>
        </li>
    </ul>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"> </i>
                {{__('auth.logout')}}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="sidebar-sticky pt-3">

                @include('admin._helpers.nav')

            </div>
        </nav>

        <div role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 pt-4">
            @yield('content')
        </div>
    </div>
</div>


<script src="{{asset('_admin/js/vendors/jquery-3.5.1.slim.min.js')}}"></script>
<script src="{{asset('_admin/js/vendors/jquery-ui.js')}}"></script>
<script src="{{asset('_admin/js/vendors/ckeditor.js')}}"></script>
{{--<script src="{{asset('_admin/js/vendors/Chart.min.js')}}"></script>--}}
<script src="{{asset('_admin/js/vendors/bootstrap.js')}}"></script>
<script src="{{asset('_admin/js/vendors/feather.min.js')}}"></script>
<script src="{{asset('_admin/js/vendors/dashboard.js')}}"></script>
<script src="{{asset('_admin/js/admin.js')}}"></script>

@stack('scripts.body.bottom')

</body>
</html>
