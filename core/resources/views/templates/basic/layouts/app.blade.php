<!doctype html>
<html lang="{{ config('app.locale') }}" data-theme="dark" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- FOUC prevention: apply saved frontend theme before first paint -->
    <script>
        (function() {
            var saved = localStorage.getItem('nekxr_frontend_theme');
            document.documentElement.setAttribute('data-theme', saved || 'dark');
        })();
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')


    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ siteFavicon() }}">

    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/odometer.css') }}">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/magnific-popup.css') }}">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/dark-mode.css') }}">

    {{-- Embedded critical dark/light mode styles --}}
    <style>
        *, *::before, *::after {
            transition: background-color .3s ease, color .15s ease, border-color .3s ease;
        }
        /* Toggle knob slide */
        html[data-theme="dark"]  .theme-toggle-btn .toggle-knob { transform:translateX(28px) !important; }
        html[data-theme="light"] .theme-toggle-btn .toggle-knob { transform:translateX(0)     !important; }
        html[data-theme="dark"]  .theme-toggle-btn .toggle-icon.sun  { opacity:.35; }
        html[data-theme="dark"]  .theme-toggle-btn .toggle-icon.moon { opacity:1; }
        html[data-theme="light"] .theme-toggle-btn .toggle-icon.sun  { opacity:1; }
        html[data-theme="light"] .theme-toggle-btn .toggle-icon.moon { opacity:.35; }
        html[data-theme="light"] .theme-toggle-btn { border-color:rgba(0,0,0,.2) !important; background:rgba(0,0,0,.06) !important; }

        /* ── DARK MODE core ── */
        html[data-theme="dark"] body              { background-color:#0d1117 !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .header           { background-color:rgba(10,14,26,.97) !important; border-bottom:1px solid rgba(240,246,252,.06) !important; box-shadow:0 2px 20px rgba(0,0,0,.6) !important; }
        html[data-theme="dark"] .nav-link,
        html[data-theme="dark"] .navbar-nav .nav-link { color:#adbac7 !important; }
        html[data-theme="dark"] .nav-link:hover   { color:#e6edf3 !important; }
        html[data-theme="dark"] .dropdown-menu    { background-color:#161b22 !important; border-color:rgba(240,246,252,.1) !important; box-shadow:0 8px 32px rgba(0,0,0,.5) !important; }
        html[data-theme="dark"] .dropdown-item    { color:#c9d1d9 !important; }
        html[data-theme="dark"] .dropdown-item:hover { background-color:#21262d !important; }
        html[data-theme="dark"] section,html[data-theme="dark"] .section { background-color:#111823 !important; }
        html[data-theme="dark"] h1,html[data-theme="dark"] h2,html[data-theme="dark"] h3,
        html[data-theme="dark"] h4,html[data-theme="dark"] h5,html[data-theme="dark"] h6 { color:#e6edf3 !important; }
        html[data-theme="dark"] p   { color:#c9d1d9; }
        html[data-theme="dark"] .card,html[data-theme="dark"] .course-card { background-color:#161b22 !important; border-color:rgba(240,246,252,.08) !important; box-shadow:0 4px 20px rgba(0,0,0,.4) !important; }
        html[data-theme="dark"] .card-body  { color:#c9d1d9 !important; background-color:#161b22 !important; }
        html[data-theme="dark"] .card-title { color:#e6edf3 !important; }
        html[data-theme="dark"] .card-footer { background-color:#1c2128 !important; border-top-color:rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .form-control,html[data-theme="dark"] textarea,
        html[data-theme="dark"] input[type=text],html[data-theme="dark"] input[type=email],
        html[data-theme="dark"] input[type=password] { background-color:#21262d !important; border-color:rgba(240,246,252,.12) !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .footer          { background-color:#040d2a !important; }
        html[data-theme="dark"] .footer-bottom   { background-color:#020716 !important; border-top-color:rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .modal-content   { background-color:#161b22 !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .modal-header    { background-color:#1c2128 !important; border-bottom-color:rgba(240,246,252,.08) !important; color:#e6edf3 !important; }
        html[data-theme="dark"] .modal-footer    { background-color:#1c2128 !important; }
        html[data-theme="dark"] .btn-close       { filter:invert(.8); }
        html[data-theme="dark"] .page-link       { background-color:#1c2128 !important; border-color:rgba(240,246,252,.1) !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .page-item.active .page-link { background-color:hsl(var(--base)) !important; }
        /* user dashboard sidebar */
        html[data-theme="dark"] .sidebar-menu    { background-color:#0f1724 !important; border-right:1px solid rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .sidebar-menu-list__link { color:#adbac7 !important; }
        html[data-theme="dark"] .sidebar-menu-list__link:hover,
        html[data-theme="dark"] .sidebar-menu-list__item.active .sidebar-menu-list__link { background-color:rgba(70,52,255,.12) !important; color:#e6edf3 !important; }
        html[data-theme="dark"] .user-profile    { background-color:#0f1724 !important; border-top-color:rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .dashboard-header { background-color:rgba(10,14,26,.95) !important; border-bottom:1px solid rgba(240,246,252,.06) !important; }
        html[data-theme="dark"] .dashboard-header__grettings { color:#e6edf3 !important; }
        html[data-theme="dark"] .text-muted      { color:#8b949e !important; }
        html[data-theme="dark"] hr               { border-color:rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .list-group-item { background-color:#161b22 !important; border-color:rgba(240,246,252,.08) !important; color:#c9d1d9 !important; }
        /* sidebar toggle label */
        html[data-theme="dark"]  .theme-label-dark  { display:inline !important; }
        html[data-theme="dark"]  .theme-label-light { display:none   !important; }
        html[data-theme="light"] .theme-label-dark  { display:none   !important; }
        html[data-theme="light"] .theme-label-light { display:inline !important; }
    </style>

    @stack('style-lib')
    @stack('style')

    @php echo loadExtension('google-analytics') @endphp

</head>

<body>

    <div class="body-overlay"></div>

    <div class="sidebar-overlay"></div>

    @yield('panel')

    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp
    @if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
        <!-- cookies dark version start -->
        <div class="cookies-card text-center hide">
            <div class="cookies-card__icon bg--base">
                <i class="las la-cookie-bite"></i>
            </div>
            <p class="mt-4 cookies-card__content">{{ $cookie->data_values->short_desc }} <a href="{{ route('cookie.policy') }}" target="_blank">@lang('learn more')</a></p>
            <div class="cookies-card__btn mt-4">
                <a href="javascript:void(0)" class="btn btn--base w-100 policy">@lang('Allow')</a>
            </div>
        </div>
        <!-- cookies dark version end -->
    @endif


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <!-- Slick js -->
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <!-- Odometer js -->
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
    <!-- Viewport js -->
    <script src="{{ asset($activeTemplateTrue . 'js/viewport.jquery.js') }}"></script>
    <!-- Magnific Popup js -->
    <script src="{{ asset($activeTemplateTrue . 'js/magnific-popup.min.js') }}"></script>
    <!-- main js -->
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/custom.js') }}"></script>

    @stack('script-lib')

    @stack('script')

    @include('partials.notify')

    @php echo loadExtension('tawk-chat') @endphp

    @if(gs('pn'))
    @include('partials.push_script')
    @endif

    <script>
        (function($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "{{ route('home') }}/change/" + $(this).val();
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            $('.select2').select2();

            $('.select2').each(function(index,element){
                $(element).select2();

            var inputElements = $('[type=text],[type=password],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

            $('.showFilterBtn').on('click',function(){
                $('.responsive-filter-card').slideToggle();
            });


            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                        colum.setAttribute('data-label', heading[i].innerText)
                    });
                });
            });


            let disableSubmission = false;
            $('.disableSubmission').on('submit',function(e){
                if (disableSubmission) {
                e.preventDefault()
                }else{
                disableSubmission = true;
                }
            });

        })(jQuery);
    </script>

    <script>
    // ===== Frontend Dark / Light Mode Toggle =====
    (function() {
        var STORAGE_KEY = 'nekxr_frontend_theme';
        var DEFAULT     = 'dark';

        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem(STORAGE_KEY, theme);
            // Update aria / tooltip labels
            document.querySelectorAll('.theme-toggle-btn').forEach(function(btn) {
                var label = theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode';
                btn.setAttribute('aria-label', label);
                btn.setAttribute('title', label);
            });
        }

        // Click delegation (header and sidebar may both have toggle)
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.theme-toggle-btn');
            if (!btn) return;
            var current = document.documentElement.getAttribute('data-theme') || DEFAULT;
            applyTheme(current === 'dark' ? 'light' : 'dark');
        });

        // On load: sync label (theme already applied by head script)
        var saved = localStorage.getItem(STORAGE_KEY) || DEFAULT;
        applyTheme(saved);
    })();
    </script>

</body>

</html>
