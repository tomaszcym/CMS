<!doctype html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {!! SEOMeta::generate() !!}

    <link href="{{asset('css/vendors/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('css/main.min.css')}}" rel="stylesheet">

    <script>
        const BASE_URL = '{{url()->to('/')}}/';
        const CSRF_TOKEN = '{{csrf_token()}}';
        const SITE_LANG = '{{app()->getLocale()}}';
    </script>

    @stack('scrips.head.bottom')
</head>
<body>

<header style="max-width: 1360px; margin: 0 auto; border-bottom: 1px solid #ccc">
    header

    @include('default.nav_item.main', ['name' => 'main'])

    @include('default._helpers.lang_nav')
</header>


<main style="max-width: 1160px; margin: 0 auto; padding: 4rem 0">
    @yield('content')
</main>


<footer style="max-width: 1360px; margin: 0 auto; border-top: 1px solid #ccc">
    footer ...
</footer>


<script src="{{asset('js/frontend.js')}}"></script>
<script src="{{asset('js/main.min.js')}}"></script>

@stack('scripts.body.bottom')
</body>
</html>
