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
                            {{-- ===== Dark / Light Mode Toggle ===== --}}
                            <li class="ms-2">
                                <button class="theme-toggle-btn"
                                        type="button"
                                        title="Toggle Theme"
                                        aria-label="Toggle dark/light mode"
                                        style="display:inline-flex;align-items:center;justify-content:space-between;position:relative;width:58px;height:28px;border-radius:999px;border:2px solid rgba(255,255,255,0.25);background:rgba(255,255,255,0.1);cursor:pointer;padding:3px 4px;overflow:hidden;flex-shrink:0;">
                                    <span class="toggle-icon sun" style="font-size:11px;z-index:2;width:18px;height:18px;display:flex;align-items:center;justify-content:center;"><i class="las la-sun"></i></span>
                                    <span class="toggle-knob" style="position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;background:#a8c0ff;box-shadow:0 1px 6px rgba(0,0,0,0.4);transition:transform 0.35s cubic-bezier(0.68,-0.55,0.27,1.55);z-index:3;"></span>
                                    <span class="toggle-icon moon" style="font-size:11px;z-index:2;width:18px;height:18px;display:flex;align-items:center;justify-content:center;"><i class="las la-moon"></i></span>
                                </button>
                            </li>
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
