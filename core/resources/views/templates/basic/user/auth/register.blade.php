@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
        $register = getContent('register.content', true);
        $socialLogin = getContent('social_login.content', true);
    @endphp
@if (gs('registration'))
    <section class="container custom-header flex-between">
        <a class="site-logo" href="{{ route('home') }}">
            <img src="{{ siteLogo() }}" alt="@lang('Logo')">
        </a>
        <p class="account-alt">
            @lang('Already have account?') <a class="text--base border-bottom border--base" href="{{ route('user.login') }}">@lang('Login Now')</a>
        </p>
    </section>

    <section class="account py-80">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-cener">
                        <h4 class="section-heading__title">{{ __(@$register->data_values->title) }}</h4>
                        <p class="section-heading__desc fs-18">{{ __(@$register->data_values->subtitle) }}</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-6 text-cener account-form">
                    <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha disableSubmission">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 col-xsm-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('First Name')</label>
                                    <input class="form--control" type="text" name="firstname" value="{{ old('firstname') }}" placeholder="@lang('Enter Your First Name')" autocomplete="firstname">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xsm-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Last Name')</label>
                                    <input class="form--control" type="text" name="lastname" value="{{ old('lastname') }}" placeholder="@lang('Enter Your Last Name')" autocomplete="lastname">
                                </div>
                            </div>
                            <div class="col-sm-12 col-xsm-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Email')</label>
                                    <input class="form--control checkUser" type="email" name="email" placeholder="@lang('Enter Your E-Mail Address')">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xsm-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Password')</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control form--control style-three @if (gs('secure_password')) secure-password @endif" placeholder="@lang('Enter Password')" name="password" value="{{ old('password') }}" required>
                                        <div class="password-show-hide fas toggle-password fa-eye-slash"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xsm-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Confirm Password')</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control form--control style-three" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="@lang('Confirm Password')" required>
                                        <div class="password-show-hide fas toggle-password fa-eye-slash"></div>
                                    </div>
                                </div>
                            </div>

                            <x-captcha/>

                            @if (gs('agree'))

                            <div class="form-group form--check">
                                <input class="form-check-input" type="checkbox" @checked(old('agree')) name="agree" id="agree" required>
                                <span class="form-check-label">
                                    <label for="agree">@lang('I agree with')</label>
                                    <span>
                                        @foreach ($policyPages as $policy)
                                            <a href="{{ route('policy.pages', $policy->slug) }}">{{ __($policy->data_values->title) }}</a>
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </span>
                                </span>
                            </div>
                            @endif
                        </div>
                        <button class="btn btn--base w-100" id="recaptcha" type="submit">@lang('Register')</button>
                    </form>

                    @include($activeTemplate . 'partials.social_login')

                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <p class="text-center mb-0">@lang('You already have an account please Login ')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="{{ route('user.login') }}" class="btn btn--base btn--sm">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>

    @else

    @include($activeTemplate.'partials.registration_disabled')

    @endif
@endsection
@if (gs('registration'))
    @if (gs('secure_password'))
        @push('script-lib')
            <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
        @endpush
    @endif
@endif
@push('script')
    <script>
        "use strict";
        (function($) {
            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                var data = {
                    email: value,
                    _token: token
                }
                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $('#existModalCenter').modal('show');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
