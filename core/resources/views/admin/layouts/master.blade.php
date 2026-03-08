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
