@php
$user = Auth::user();
$roles = ($user) ? $user->roles()->get() : [];
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" auth_user="{{ json_encode($user) }}" auth_user_roles="{{ json_encode($roles) }}">

        {{-- #APP-NAVBAR --}}
        <nav id="app-navbar" class="navbar navbar-inverse navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">

                    {{-- Collapsed Hamburger --}}
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    {{-- Branding Image --}}
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', null) }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    {{-- Left Side Of Navbar --}}
<!--                    <ul class="nav navbar-nav">-->
<!--                        &nbsp;-->
<!--                    </ul>-->

                    {{-- Right Side Of Navbar --}}
                    <ul class="nav navbar-nav navbar-right">
                        {{-- Authentication Links --}}
                        @if (Auth::guest())
                            <li>
                                <a href="{{ route('login') }}">
                                    <i class="fa fa-sign-in"></i>
                                    Login
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}">
                                    <i class="fa fa-user"></i>
                                    Register
                                </a>
                            </li>
                        @else
                            <li id="navbar-user-menu" class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ $user->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    @if($user->hasRole('ADMIN'))
                                        <li>
                                            <a href="/admin/users">
                                                <i class="fa fa-users"></i>
                                                Users
                                            </a>
                                        </li>

                                        <div class="divider"></div>
                                    @endif

                                    <li>
                                        <a href="{{ route('logout') }}"  onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out"></i>
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        {{-- APP CONTENT --}}
        <div id="app-content">
            @yield('content')
        </div>
    </div>

    {{--  --}}
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
