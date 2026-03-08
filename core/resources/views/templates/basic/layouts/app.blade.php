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
        html[data-theme="dark"] .header.fixed,
        html[data-theme="dark"] .header.sticky    { background-color:rgba(10,14,26,.99) !important; }
        html[data-theme="dark"] .main-wrapper     { background-color:#0d1117 !important; }
        html[data-theme="dark"] .nav-link,
        html[data-theme="dark"] .navbar-nav .nav-link { color:#adbac7 !important; }
        html[data-theme="dark"] .nav-link:hover   { color:#e6edf3 !important; }
        html[data-theme="dark"] .dropdown-menu    { background-color:#161b22 !important; border-color:rgba(240,246,252,.1) !important; box-shadow:0 8px 32px rgba(0,0,0,.5) !important; }
        html[data-theme="dark"] .dropdown-item    { color:#c9d1d9 !important; }
        html[data-theme="dark"] .dropdown-item:hover { background-color:#21262d !important; }
        html[data-theme="dark"] section           { background-color:#111823 !important; }
        html[data-theme="dark"] .section-bg       { background-color:#111823 !important; }
        html[data-theme="dark"] h1,html[data-theme="dark"] h2,html[data-theme="dark"] h3,
        html[data-theme="dark"] h4,html[data-theme="dark"] h5,html[data-theme="dark"] h6 { color:#e6edf3 !important; }
        html[data-theme="dark"] p   { color:#c9d1d9; }
        /* Bootstrap .bg-white/.bg-light overrides - higher specificity beats Bootstrap's !important */
        html[data-theme="dark"] .bg-white  { background-color:#161b22 !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .bg-light  { background-color:#1c2128 !important; color:#c9d1d9 !important; }
        /* Cards - generic + specific landing page card types */
        html[data-theme="dark"] .card,
        html[data-theme="dark"] .course-card,
        html[data-theme="dark"] .custom--card,
        html[data-theme="dark"] .courses-card    { background-color:#161b22 !important; border-color:rgba(240,246,252,.08) !important; box-shadow:0 4px 20px rgba(0,0,0,.4) !important; }
        html[data-theme="dark"] .card-body,
        html[data-theme="dark"] .custom--card .card-body,
        html[data-theme="dark"] .course-card .card-body  { color:#c9d1d9 !important; background-color:#161b22 !important; }
        html[data-theme="dark"] .card-title { color:#e6edf3 !important; }
        html[data-theme="dark"] .card-header,
        html[data-theme="dark"] .custom--card .card-header { background-color:#1c2128 !important; border-bottom-color:rgba(240,246,252,.08) !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .card-footer { background-color:#1c2128 !important; border-top-color:rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .courses-card-icon { background-color:#21262d !important; }
        /* Banner floating cards */
        html[data-theme="dark"] .banner-floating-cards .floating-card { background-color:#1c2128 !important; border-color:rgba(240,246,252,.15) !important; box-shadow:none !important; }
        /* User dashboard course item */
        html[data-theme="dark"] .course          { background-color:#161b22 !important; border-color:rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .course__name    { color:#e6edf3 !important; }
        html[data-theme="dark"] .dashboard-body,
        html[data-theme="dark"] .dashboard__right,
        html[data-theme="dark"] .my-course       { background-color:#0d1117 !important; }
        /* Forms */
        html[data-theme="dark"] .form-control,html[data-theme="dark"] textarea,
        html[data-theme="dark"] input[type=text],html[data-theme="dark"] input[type=email],
        html[data-theme="dark"] input[type=password],html[data-theme="dark"] input[type=number],
        html[data-theme="dark"] input[type=search] { background-color:#21262d !important; border-color:rgba(240,246,252,.12) !important; color:#c9d1d9 !important; }
        /* Footer */
        html[data-theme="dark"] .footer          { background-color:#040d2a !important; }
        html[data-theme="dark"] .footer-bottom   { background-color:#020716 !important; border-top-color:rgba(240,246,252,.08) !important; }
        /* Modals */
        html[data-theme="dark"] .modal-content   { background-color:#161b22 !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .modal-header    { background-color:#1c2128 !important; border-bottom-color:rgba(240,246,252,.08) !important; color:#e6edf3 !important; }
        html[data-theme="dark"] .modal-footer    { background-color:#1c2128 !important; }
        html[data-theme="dark"] .btn-close       { filter:invert(.8); }
        /* Pagination */
        html[data-theme="dark"] .page-link       { background-color:#1c2128 !important; border-color:rgba(240,246,252,.1) !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .page-item.active .page-link { background-color:hsl(var(--base)) !important; }
        /* User dashboard sidebar */
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

        /* ── LIGHT MODE explicit reverts ── */
        html[data-theme="light"] body            { background-color:#f5f7fa !important; color:hsl(60,2%,10%) !important; }
        html[data-theme="light"] .header         { background-color:#fff !important; border-bottom-color:rgba(0,0,0,.08) !important; box-shadow:0 1px 4px rgba(0,0,0,.06) !important; }
        html[data-theme="light"] .sidebar-menu   { background-color:#fff !important; border-right-color:rgba(0,0,0,.1) !important; }
        html[data-theme="light"] .user-profile   { background-color:#fff !important; border-top-color:rgba(0,0,0,.06) !important; }
        html[data-theme="light"] .dashboard,
        html[data-theme="light"] .dashboard__right,
        html[data-theme="light"] .dashboard-body,
        html[data-theme="light"] .my-course      { background-color:#f5f7fa !important; }
        html[data-theme="light"] .dashboard-header { background-color:#fff !important; border-bottom:1px solid rgba(0,0,0,.06) !important; box-shadow:0 1px 4px rgba(0,0,0,.05) !important; backdrop-filter:none !important; }
        html[data-theme="light"] .card,
        html[data-theme="light"] .course-card,
        html[data-theme="light"] .custom--card,
        html[data-theme="light"] .courses-card,
        html[data-theme="light"] .course         { background-color:#fff !important; border-color:rgba(0,0,0,.08) !important; box-shadow:0 2px 8px rgba(0,0,0,.06) !important; }
        html[data-theme="light"] .card-header    { background-color:transparent !important; border-bottom-color:rgba(0,0,0,.08) !important; color:hsl(239,92%,14%) !important; }
        html[data-theme="light"] .card-body      { background-color:#fff !important; color:hsl(60,2%,10%) !important; }
        html[data-theme="light"] .bg-white        { background-color:#fff !important; color:hsl(60,2%,10%) !important; }
        html[data-theme="light"] .nav-link       { color:hsl(239,92%,14%) !important; }
        html[data-theme="light"] h1,html[data-theme="light"] h2,html[data-theme="light"] h3,
        html[data-theme="light"] h4,html[data-theme="light"] h5,html[data-theme="light"] h6 { color:hsl(239,92%,14%) !important; }
        html[data-theme="light"] .dropdown-menu  { background-color:#fff !important; border-color:rgba(0,0,0,.1) !important; }
        html[data-theme="light"] .dropdown-item  { color:hsl(239,92%,14%) !important; }
        html[data-theme="light"] .text-muted     { color:#6c757d !important; }
        html[data-theme="light"] .theme-toggle-btn { border-color:rgba(0,0,0,.18) !important; background:rgba(0,0,0,.07) !important; }
        html[data-theme="light"] .theme-toggle-btn .toggle-knob { background:#ffd700 !important; }
        html[data-theme="light"] .modal-content  { background-color:#fff !important; color:hsl(60,2%,10%) !important; }
        html[data-theme="light"] .modal-header   { background-color:#f8f9fa !important; border-bottom-color:rgba(0,0,0,.1) !important; color:hsl(239,92%,14%) !important; }
        html[data-theme="light"] .btn-close      { filter:none !important; }

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
