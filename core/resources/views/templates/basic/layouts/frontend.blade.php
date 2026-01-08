@extends($activeTemplate . 'layouts.app')
@section('panel')
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/color.php') }}?color={{ gs('base_color') }}">

    <div class="preloader">
        <div class="loader-p"></div>
    </div>

    <a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>
    
    @php
    $authRoute = request()->routeIs('user.login') || request()->routeIs('user.register') || request()->routeIs('maintenance');
    @endphp

    @if (!$authRoute)
    @include($activeTemplate . 'partials.header')
    @endif

    @yield('content')

    @if (!$authRoute)
    @include($activeTemplate . 'partials.footer')
    @endif

@endsection
