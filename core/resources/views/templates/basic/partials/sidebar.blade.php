<div class="sidebar-menu">
    <div class="sidebar-menu__inner">
        <span class="sidebar-menu__close d-lg-none d-block"><i class="far fa-times"></i></span>
        <!-- Sidebar Logo Start -->
        <div class="sidebar-logo">
            <a href="{{ route('home') }}" class="sidebar-logo__link"><img src="{{ siteLogo() }}" alt="@lang('Image')"></a>
        </div>
        <!-- Sidebar Logo End -->

        <!-- ========= Sidebar Menu Start ================ -->
        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item {{ menuActive('user.home') }}">
                <a href="{{ route('user.home') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-tachometer-alt"></i></span>
                    <span class="text">@lang('Dashboard')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive('user.course.*') }}">
                <a href="{{ route('user.course.list') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-laptop-code"></i></span>
                    <span class="text">@lang('My Course')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive('user.deposit.history') }}">
                <a href="{{ route('user.deposit.history') }}" class="sidebar-menu-list__link">
                    <span class="icon">
                        <i class="las la-history"></i>
                    </span>
                    <span class="text">@lang('Payment History')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive('user.review') }}">
                <a href="{{ route('user.review') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="lar la-star"></i>
                    </span>
                    <span class="text">@lang('Review')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive('ticket.*') }}">
                <a href="{{ route('ticket.index') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-ticket-alt"></i></span>
                    <span class="text">@lang('Support')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive('user.profile.setting') }}">
                <a href="{{ route('user.profile.setting') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-user"></i></span>
                    <span class="text">@lang('Profile Setting')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive('user.change.password') }}">
                <a href="{{ route('user.change.password') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-key"></i></span>
                    <span class="text">@lang('Change Password')</span>
                </a>
            </li>
        </ul>
        <!-- ========= Sidebar Menu End ================ -->
    </div>
    <!-- ========= User Profile Info Start ================ -->
    <div class="user-profile">
        <a href="{{ route('user.profile.setting') }}" class="user-profile-info">
            <span class="user-profile-info__icon">
                <i class="las la-user"></i>
            </span>
            <div class="user-profile-info__content">
                <span class="user-profile-info__name">{{ auth()->user()->fullname }}</span>
                <p class="user-profile-info__desc">{{ '@' . auth()->user()->username }}</p>
            </div>
        </a>

        <div class="user-profile__dropdown">
            <button class="user-profile-dots dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-angle-down"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="exportMenuButton">
                <a class="dropdown-item" href="{{ route('user.profile.setting') }}">
                    <span class="icon"><i class="fa fa-cog"></i></span>
                    <span class="icon">@lang('Profile Setting')</span>
                </a>
                <a class="dropdown-item" href="{{ route('user.logout') }}">
                    <span class="icon"><i class="fa fa-sign-out-alt"></i></span>
                    <span class="icon">@lang('Logout')</span>
                </a>
            </div>
        </div>
    </div>
    <!-- ========= User Profile Info End ================ -->
</div>
@push('style')
<style>
    .user-profile .dropdown-toggle::after {
        display: none;
    }
</style>
@endpush
