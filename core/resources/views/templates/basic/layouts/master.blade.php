@extends($activeTemplate . 'layouts.app')
@section('panel')

<link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'dashboard/css/main.css') }}">
<link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/color.php') }}?color={{ gs('base_color') }}">


<div class="preloader">
    <div class="preloader__favicon">
        <img src="{{ siteFavicon() }}" alt="@lang('Image')">
    </div>
    <div class="preloader__circle">
    </div>
</div>

    <div class="dashboard position-relative">
        <div class="dashboard__inner">

            @include($activeTemplate . 'partials.sidebar')

            <div class="dashboard__right">

                <div class="dashboard-header">
                    <div class="dashboard-header__inner">
                        <div class="dashboard-header__left">
                            <h1 class="dashboard-header__grettings mb-0">{{ __($pageTitle) }}</h1>
                        </div>
                        <ul class="d-flex align-items-center dashboard-topbar">
                            @stack('breadcrumb-buttons')
                            <div class="dashboard__bar d-lg-none d-block">
                                <span class="dashboard-body__bar-icon"><i class="fas fa-bars"></i></span>
                            </div>
                        </ul>
                    </div>
                </div>
                @yield('content')

            </div>
            <!-- Dashboar Body End -->
        </div>
    </div>
@endsection
