<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hotel Management System') }} @yield('title')</title>

    <!-- Scripts & Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div id="app">
        <!-- Modern Glassmorphism Navbar -->
        <nav class="navbar navbar-expand-md navbar-dark bg-transparent fixed-top glass-panel pt-3 pb-3 m-3">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="{{ url('/') }}">
                    <i class="bi bi-building me-2"></i> {{ config('app.name', 'HMS 2.0') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reservations.calendar') }}"><i class="bi bi-calendar-event"></i> Calendar</a>
                        </li>
                        
                        @if(Auth::check() && in_array(Auth::user()->role->slug ?? 'guest', ['admin', 'manager', 'staff']))
                        <li class="nav-item ps-2">
                            <a class="nav-link" href="{{ route('hotels.index') }}"><i class="bi bi-buildings"></i> Hotels</a>
                        </li>
                        <li class="nav-item ps-2">
                            <a class="nav-link" href="{{ route('rooms.index') }}"><i class="bi bi-box-seam"></i> Rooms</a>
                        </li>
                        @endif

                        @if(Auth::check() && in_array(Auth::user()->role->slug ?? 'guest', ['admin', 'manager']))
                        <li class="nav-item ps-2">
                            <a class="nav-link" href="{{ route('staff.index') }}"><i class="bi bi-people-fill"></i> Staff</a>
                        </li>
                        @endif

                        @if(Auth::check() && in_array(Auth::user()->role->slug ?? 'guest', ['admin', 'manager', 'staff']))
                        <li class="nav-item ps-2">
                            <a class="nav-link" href="{{ route('customers.index') }}"><i class="bi bi-person-vcard"></i> CRM</a>
                        </li>
                        @endif

                        @if(Auth::check() && in_array(Auth::user()->role->slug ?? 'guest', ['admin', 'manager']))
                        <li class="nav-item ps-2">
                            <a class="nav-link text-info" href="{{ route('reports.index') }}"><i class="bi bi-graph-up"></i> Reports</a>
                        </li>
                        @endif

                        @if(Auth::check() && in_array(Auth::user()->role->slug ?? 'guest', ['admin', 'manager', 'staff']))
                        <li class="nav-item ps-2">
                            <a class="nav-link text-warning" href="{{ route('billing.index') }}"><i class="bi bi-wallet2"></i> Billing</a>
                        </li>
                        @endif

                        @if(Auth::check() && (Auth::user()->role->slug ?? 'guest') === 'admin')
                        <li class="nav-item ps-2 border-start border-secondary ms-2 ps-3 border-opacity-50">
                            <span class="nav-link text-muted"><i class="bi bi-shield-lock-fill"></i> Admin Console:</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="{{ route('users.index') }}"><i class="bi bi-diagram-3-fill"></i> Users</a>
                        </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-primary hover-lift ms-2 rounded-pill px-4" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end glass-panel" aria-labelledby="navbarDropdown">
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
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container-fluid" style="padding-top: 100px;">
            @yield('content')
        </main>
    </div>
</body>
</html>
