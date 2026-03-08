@php
    $categories = App\Models\Category::active()->orderBy('name')->get();
@endphp

<header class="header">
    <div class="custom--container">
        <nav class="navbar navbar-expand-lg header-navbar">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ siteLogo() }}" alt="@lang('Logo')">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#header-navbar-collapse" aria-expanded="false">
                <i class="fas fa-bars"></i>
            </button>
            <div id="header-navbar-collapse" class="collapse navbar-collapse">
                <ul class="navbar-nav nav-menu ms-auto align-items-lg-center">
                    <li class="nav-item {{ menuActive('home') }}">
                        <a class="nav-link" href="{{ route('home') }}">@lang('Home')</a>
                    </li>
                    <li class="nav-item {{ menuActive(['courses', 'course.details', 'course.lesson']) }}">
                        <a class="nav-link" href="{{ route('courses') }}">@lang('Courses')</a>
                    </li>
                    @if ($categories->count())
                        <li class="nav-item dropdown {{ menuActive('category.course') }}">
                            <a class="nav-link" href="javascript:void(0)" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                @lang('Categories') <span class="nav-item__icon"><i class="las la-angle-down"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($categories as $category)
                                    <li class="dropdown-menu__list">
                                        <a class="dropdown-item dropdown-menu__link"
                                            href="{{ route('category.course', [slug($category->name), $category->id]) }}">{{ __($category->name) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                    @php
                        $pages = App\Models\Page::where('tempname', $activeTemplate)->where('is_default', 0)->get();
                    @endphp
                    @foreach ($pages as $k => $data)
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('pages', null, $data->slug) }}"
                                href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a>
                        </li>
                    @endforeach
                    <li class="nav-item {{ menuActive('contact') }}">
                        <a class="nav-link" href="{{ route('contact') }}">@lang('Contact')</a>
                    </li>
                </ul>
                <div class="navbar-buttons">
                    {{-- ===== Dark / Light Mode Toggle ===== --}}
                    <button class="theme-toggle-btn"
                            type="button"
                            title="Toggle Theme"
                            aria-label="Toggle dark/light mode"
                            style="display:inline-flex;align-items:center;justify-content:space-between;position:relative;width:58px;height:28px;border-radius:999px;border:2px solid rgba(255,255,255,0.25);background:rgba(255,255,255,0.1);cursor:pointer;padding:3px 4px;overflow:hidden;flex-shrink:0;">
                        <span class="toggle-icon sun" style="font-size:11px;z-index:2;width:18px;height:18px;display:flex;align-items:center;justify-content:center;"><i class="las la-sun"></i></span>
                        <span class="toggle-knob" style="position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;background:#a8c0ff;box-shadow:0 1px 6px rgba(0,0,0,0.4);transition:transform 0.35s cubic-bezier(0.68,-0.55,0.27,1.55);z-index:3;"></span>
                        <span class="toggle-icon moon" style="font-size:11px;z-index:2;width:18px;height:18px;display:flex;align-items:center;justify-content:center;"><i class="las la-moon"></i></span>
                    </button>
                    @guest
                        <a class="btn btn--rounded btn--light-two navbar-login-btn" href="{{ route('user.login') }}"
                            role="button">
                            <div class="las la-user-alt"></div>
                            <span>@lang('Login')</span>
                        </a>
                        <a class="btn btn--rounded btn--base navbar-enroll-btn" href="{{ route('user.register') }}"
                            role="button">
                            <i class="las la-plus-circle"></i>
                            @lang('Register')
                        </a>
                    @else
                        <a class="btn btn--rounded btn--light-two navbar-login-btn" href="{{ route('user.home') }}"
                            role="button">
                            <div class="las la-user-alt"></div>
                            <span>@lang('Dashboard')</span>
                        </a>
                        <a class="btn btn--rounded btn--base navbar-enroll-btn" href="{{ route('user.logout') }}"
                            role="button">
                            <i class="las la-sign-out-alt"></i>
                            @lang('Logout')
                        </a>
                    @endguest
                </div>
            </div>
        </nav>
    </div>
</header>
