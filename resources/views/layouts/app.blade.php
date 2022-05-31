<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
{{--    <link rel="stylesheet" type="text/css" href="../../sass/app.scss"/>--}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '勤怠管理システム') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('styles')
    @yield('styles_basic')
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm fixed-top">
        <div class="container noselect">
            @if(Auth::check())
                <span class="d-inline d-lg-none sidebarBtn" id="sectionTitle"><span
                        id="sectionTitle3">×</span> MENU <span
                        id="sectionTitle2">▶</span></span>
            @endif
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', '勤怠管理システム') }}
            </a>
            @if (env('ENABLE_NAV_TITLE', true) && View::hasSection('pageTitle'))
                <span class="d-none d-sm-inline title-section-splitter">|</span>
                    <span class="d-none d-sm-inline text-white title-fs">@yield('pageTitle')</span>
            @endif
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    @if(env('ENABLE_NAV_CLOCK', true))
                        <li class="nav-item d-lg-inline d-md-none d-none">
                            <span id="navCurrentTime" class="nav-link text-white fw-bold clock">時刻取得中</span>
                        </li>
                    @endif
                <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        @if(env('ENABLE_NAV_LOGOUT', true))
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if(env('ENABLE_NAV_NAME', true))
                                        {{ Auth::user()->name }}
                                    @else
                                        オプション
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @else
                            @if(env('ENABLE_NAV_NAME', true))
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        {{ Auth::user()->name }}
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('basement')
    </main>
</div>
@yield('modal')
<script defer>
    @if(env('ENABLE_NAV_CLOCK', true))
    function updateDisplayTime() {
        let date = new Date()
        let years = date.getFullYear()
        let months = date.getMonth() + 1
        let days = date.getDate()
        let hours = date.getHours()
        let minutes = ('00' + date.getMinutes()).slice(-2)
        let seconds = ('00' + date.getSeconds()).slice(-2)
        let dayOfWeek = date.getDay()
        let dayOfWeekArrayStr = ["日", "月", "火", "水", "木", "金", "土"][dayOfWeek]
        document.getElementById("navCurrentTime").innerHTML = years + "/" + months + "/" + days + " (" + dayOfWeekArrayStr + ") " + hours + ":" + minutes + ":" + seconds
    }

    setInterval('updateDisplayTime()', 1000)

    @endif
    function jump(link) {
        location = link
    }

    function href(url) {
        location = url
    }

    function hrefBlank(url) {
        window.open(url, '_blank')
    }
</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"
        integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em"
        crossorigin="anonymous"></script>
</body>
</html>
