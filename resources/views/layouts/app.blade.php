<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="author" content="@sellu">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/utg.logo.png') }}" type="image/x-icon">
    <title>{{ config('app.name') }}</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
</head>
<?php $auth = Auth::user(); ?>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('img/utg.logo.png') }}" width="20px">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    @guest
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        </ul>
                    @else
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            <li>
                                <a class="nav-link active" href="{{ route('folders.home') }}">
                                    <i class="fa fa-home"></i> {{ __('Home Folder') }}
                                </a>
                            </li>
                            @if($auth->is_admin())
                            <li>
                                <a class="nav-link" href="{{ route('admin.index') }}">
                                    <i class="fa fa-dashboard"></i> {{ __('Admin Panel') }}
                                </a>
                            </li>
                            @endif
                        </ul>
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ $auth->name }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('profile') }}">
                                            <i class="fa fa-user"></i> {{ __('Profile') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out"></i> {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                        </ul>
                    @endguest
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if (session('status'))
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('status') }}
                        </div>
                    </div>
                </div>
                @endif
                @if(count($errors) > 0)
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <ul class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
