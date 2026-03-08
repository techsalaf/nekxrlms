<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <!-- FOUC prevention: apply saved admin theme before first paint -->
    <script>
        (function() {
            var saved = localStorage.getItem('nekxr_admin_theme');
            document.documentElement.setAttribute('data-theme', saved || 'dark');
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs()->siteName($pageTitle ?? '') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{siteFavicon()}}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/bootstrap-toggle.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/global/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/global/css/line-awesome.min.css')}}">

    @stack('style-lib')

    <link rel="stylesheet" href="{{asset('assets/global/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/dark-mode.css')}}">

    {{-- Embedded critical dark/light mode styles (guaranteed to load) --}}
    <style>
        /* ── Smooth colour transitions ── */
        *, *::before, *::after {
            transition: background-color .3s ease, color .15s ease, border-color .3s ease, box-shadow .3s ease;
        }
        /* ── Toggle knob animation ── */
        html[data-theme="dark"]  .theme-toggle-btn .toggle-knob { transform: translateX(28px) !important; }
        html[data-theme="light"] .theme-toggle-btn .toggle-knob { transform: translateX(0)     !important; }
        /* icon opacity */
        html[data-theme="dark"]  .theme-toggle-btn .toggle-icon.sun  { opacity:.35; }
        html[data-theme="dark"]  .theme-toggle-btn .toggle-icon.moon { opacity:1;   }
        html[data-theme="light"] .theme-toggle-btn .toggle-icon.sun  { opacity:1;   }
        html[data-theme="light"] .theme-toggle-btn .toggle-icon.moon { opacity:.35; }

        /* ── DARK MODE: core page chrome ── */
        html[data-theme="dark"] body                { background-color:#0d1117 !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .page-wrapper        { background-color:#0d1117 !important; }
        html[data-theme="dark"] .body-wrapper        { background-color:#0d1117 !important; }
        html[data-theme="dark"] .bodywrapper__inner  { background-color:#0d1117 !important; }
        html[data-theme="dark"] .container-fluid     { background-color:#0d1117 !important; }
        /* sidebar */
        html[data-theme="dark"] .sidebar { background:#0f1724 !important; border-right-color:rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .sidebar .sidebar__logo { background-color:#0f1724 !important; }
        /* cards */
        html[data-theme="dark"] .card               { background-color:#161b22 !important; border-color:rgba(240,246,252,.08) !important; box-shadow:0 1px 10px rgba(0,0,0,.4) !important; }
        html[data-theme="dark"] .card-header        { background-color:#1c2128 !important; border-bottom-color:rgba(240,246,252,.08) !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .card-footer        { background-color:#1c2128 !important; border-top-color:rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .card-body          { color:#c9d1d9 !important; }
        /* tables */
        html[data-theme="dark"] .table              { color:#c9d1d9 !important; border-color:rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .table th           { background-color:#1c2128 !important; color:#adbac7 !important; border-color:rgba(240,246,252,.1) !important; }
        html[data-theme="dark"] .table td           { border-color:rgba(240,246,252,.06) !important; color:#c9d1d9 !important; }
        /* headings */
        html[data-theme="dark"] h1,html[data-theme="dark"] h2,html[data-theme="dark"] h3,
        html[data-theme="dark"] h4,html[data-theme="dark"] h5,html[data-theme="dark"] h6 { color:#e6edf3 !important; }
        /* forms */
        html[data-theme="dark"] .form-control,html[data-theme="dark"] textarea,
        html[data-theme="dark"] input[type=text],html[data-theme="dark"] input[type=email],
        html[data-theme="dark"] input[type=password],html[data-theme="dark"] input[type=number],
        html[data-theme="dark"] input[type=search],html[data-theme="dark"] select {
            background-color:#21262d !important; border-color:rgba(240,246,252,.12) !important; color:#c9d1d9 !important;
        }
        html[data-theme="dark"] .input-group-text   { background-color:#1c2128 !important; border-color:rgba(240,246,252,.12) !important; color:#8b949e !important; }
        html[data-theme="dark"] .form-select        { background-color:#21262d !important; border-color:rgba(240,246,252,.12) !important; color:#c9d1d9 !important; }
        /* dropdowns */
        html[data-theme="dark"] .dropdown-menu      { background-color:#161b22 !important; border-color:rgba(240,246,252,.1) !important; box-shadow:0 8px 30px rgba(0,0,0,.5) !important; }
        html[data-theme="dark"] .dropdown-item      { color:#c9d1d9 !important; }
        html[data-theme="dark"] .dropdown-item:hover{ background-color:#21262d !important; }
        html[data-theme="dark"] .dropdown-menu__header,
        html[data-theme="dark"] .dropdown-menu__footer { background-color:#1c2128 !important; border-color:rgba(240,246,252,.08) !important; }
        /* modals */
        html[data-theme="dark"] .modal-content      { background-color:#161b22 !important; border-color:rgba(240,246,252,.08) !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .modal-header        { background-color:#1c2128 !important; border-bottom-color:rgba(240,246,252,.08) !important; color:#e6edf3 !important; }
        html[data-theme="dark"] .modal-footer        { background-color:#1c2128 !important; border-top-color:rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .btn-close           { filter:invert(.8); }
        /* alerts */
        html[data-theme="dark"] .alert-warning { background-color:rgba(255,159,67,.1) !important; color:#ffcd91 !important; }
        html[data-theme="dark"] .alert-danger  { background-color:rgba(235,34,34,.1) !important; color:#f89898 !important; }
        html[data-theme="dark"] .alert-success { background-color:rgba(40,199,111,.1) !important; color:#74e8a6 !important; }
        html[data-theme="dark"] .alert-info    { background-color:rgba(30,159,242,.1) !important; color:#78c8f7 !important; }
        /* breadcrumb */
        html[data-theme="dark"] .breadcrumb            { background-color:#1c2128 !important; }
        html[data-theme="dark"] .breadcrumb-item a      { color:#8b949e !important; }
        html[data-theme="dark"] .breadcrumb-item.active  { color:#c9d1d9 !important; }
        html[data-theme="dark"] .breadcrumb-item+.breadcrumb-item::before { color:#8b949e !important; }
        /* pagination */
        html[data-theme="dark"] .page-link       { background-color:#1c2128 !important; border-color:rgba(240,246,252,.1) !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .page-item.active .page-link { background-color:#4634ff !important; border-color:#4634ff !important; color:#fff !important; }
        /* misc */
        html[data-theme="dark"] hr               { border-color:rgba(240,246,252,.08) !important; }
        html[data-theme="dark"] .list-group-item { background-color:#161b22 !important; border-color:rgba(240,246,252,.08) !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .text-muted      { color:#8b949e !important; }
        html[data-theme="dark"] .progress        { background-color:#1c2128 !important; }
        /* select2 */
        html[data-theme="dark"] .select2-container--default .select2-selection--single,
        html[data-theme="dark"] .select2-container--default .select2-selection--multiple { background-color:#21262d !important; border-color:rgba(240,246,252,.12) !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered { color:#c9d1d9 !important; }
        html[data-theme="dark"] .select2-dropdown { background-color:#1c2128 !important; border-color:rgba(240,246,252,.12) !important; }
        html[data-theme="dark"] .select2-container--default .select2-results__option { background-color:#21262d !important; color:#c9d1d9 !important; }
        html[data-theme="dark"] .select2-container--default .select2-results__option--highlighted { background-color:#4634ff !important; color:#fff !important; }
        html[data-theme="dark"] .select2-search--dropdown .select2-search__field { background-color:#21262d !important; border-color:rgba(240,246,252,.12) !important; color:#c9d1d9 !important; }
        /* sidebar menu links */
        html[data-theme="dark"] .sidebar-menu-item a,.sidebar .sidebar__menu a       { color:#adbac7 !important; }
        html[data-theme="dark"] .sidebar-menu-item a:hover,.sidebar-menu-item.active a { background-color:rgba(70,52,255,.12) !important; color:#c9d1d9 !important; }

        /* ── LIGHT MODE: revert to white ── */
        html[data-theme="light"] body           { background-color:#f0f2f5 !important; color:#34495e !important; }
        html[data-theme="light"] .page-wrapper,
        html[data-theme="light"] .body-wrapper,
        html[data-theme="light"] .bodywrapper__inner,
        html[data-theme="light"] .container-fluid { background-color:#f0f2f5 !important; }
        html[data-theme="light"] .sidebar        { background-color:#fff !important; border-right-color:#66666675 !important; }
        html[data-theme="light"] .card           { background-color:#fff !important; box-shadow:0 .75rem 1.5rem rgba(18,38,63,.03) !important; }
        html[data-theme="light"] .card-header    { background-color:transparent !important; border-bottom-color:rgba(140,140,140,.125) !important; color:#34495e !important; }
        html[data-theme="light"] .card-footer    { background-color:#fff !important; border-top-color:#e8e8e8 !important; }
        html[data-theme="light"] .form-control,html[data-theme="light"] input,
        html[data-theme="light"] textarea,html[data-theme="light"] select { background-color:#fff !important; border-color:#ced4da !important; color:#495057 !important; }
        html[data-theme="light"] .dropdown-menu  { background-color:#fff !important; }
        html[data-theme="light"] .dropdown-item  { color:#34495e !important; }
        html[data-theme="light"] h1,html[data-theme="light"] h2,html[data-theme="light"] h3,
        html[data-theme="light"] h4,html[data-theme="light"] h5,html[data-theme="light"] h6 { color:#34495e !important; }
        html[data-theme="light"] .theme-toggle-btn { border-color:rgba(0,0,0,.2) !important; background:rgba(0,0,0,.06) !important; }
    </style>

    @stack('style')
</head>
<body>
@yield('content')




<script src="{{asset('assets/global/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/global/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/admin/js/vendor/bootstrap-toggle.min.js')}}"></script>


@include('partials.notify')
@stack('script-lib')

<script src="{{ asset('assets/global/js/nicEdit.js') }}"></script>

<script src="{{asset('assets/global/js/select2.min.js')}}"></script>
<script src="{{asset('assets/admin/js/app.js')}}"></script>

{{-- LOAD NIC EDIT --}}
<script>
    "use strict";
    bkLib.onDomLoaded(function() {
        $( ".nicEdit" ).each(function( index ) {
            $(this).attr("id","nicEditor"+index);
            new nicEditor({fullPanel : true}).panelInstance('nicEditor'+index,{hasPanel : true});
        });
    });
    (function($){
        $( document ).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain',function(){
            $('.nicEdit-main').focus();
        });

        $('.breadcrumb-nav-open').on('click', function() {
            $(this).toggleClass('active');
            $('.breadcrumb-nav').toggleClass('active');
        });

        $('.breadcrumb-nav-close').on('click', function() {
            $('.breadcrumb-nav').removeClass('active');
        });

        if($('.topTap').length){
            $('.breadcrumb-nav-open').removeClass('d-none');
        }
    })(jQuery);
</script>

@stack('script')

<script>
"use strict";
// ===== Admin Dark / Light Mode Toggle =====
(function() {
    var STORAGE_KEY = 'nekxr_admin_theme';
    var DEFAULT     = 'dark';

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem(STORAGE_KEY, theme);
        // Update tooltip text on all toggle buttons
        document.querySelectorAll('.theme-toggle-btn').forEach(function(btn) {
            var title = theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode';
            btn.setAttribute('title', title);
            btn.setAttribute('data-bs-original-title', title);
        });
    }

    // Delegate click on any .theme-toggle-btn (topnav may load late)
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.theme-toggle-btn');
        if (!btn) return;
        var current = document.documentElement.getAttribute('data-theme') || DEFAULT;
        applyTheme(current === 'dark' ? 'light' : 'dark');
    });

    // Set initial title attribute
    var saved = localStorage.getItem(STORAGE_KEY) || DEFAULT;
    applyTheme(saved);
})();
</script>

</body>
</html>
