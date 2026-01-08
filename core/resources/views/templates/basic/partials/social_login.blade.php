@php
    $text = request()->routeIs('user.register') ? 'Register' : 'Login';
@endphp

@if (
    @gs('socialite_credentials')->linkedin->status ||
        @gs('socialite_credentials')->facebook->status == Status::ENABLE ||
        @gs('socialite_credentials')->google->status == Status::ENABLE)
    <div class="auth-devide">
        <span>@lang('OR')</span>
    </div>
@endif

<div class="social-auth">
    @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
        <div class="continue-google">
            <a href="{{ route('user.social.login', 'google') }}" class="social-login-btn">
                <span class="google-icon">
                    <img src="{{ asset($activeTemplateTrue . 'images/google.svg') }}" alt="Google">
                </span> @lang("$text with Google")
            </a>
        </div>
    @endif

    @if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
        <div class="continue-facebook">
            <a href="{{ route('user.social.login', 'facebook') }}" class="social-login-btn">
                <span class="facebook-icon">
                    <img src="{{ asset($activeTemplateTrue . 'images/facebook.svg') }}" alt="Facebook">
                </span> @lang("$text with Facebook")
            </a>
        </div>
    @endif

    @if (@gs('socialite_credentials')->linkedin->status == Status::ENABLE)
        <div class="linkedin-facebook">
            <a href="{{ route('user.social.login', 'linkedin') }}" class="social-login-btn">
                <span class="linkedin-icon">
                    <img src="{{ asset($activeTemplateTrue . 'images/linkdin.svg') }}" alt="Linkedin">
                </span> @lang("$text with Linkedin")
            </a>
        </div>
    @endif
</div>

@push('style')
    <style>
        .social-login-btn {
            border: 1px solid hsl(var(--black) / 0.2);
            padding: 12px 0px;
            border-radius: 4px;
            width: 100%;
            display: flex;
            align-content: center;
            gap: 12px;
            justify-content: center;
            color: hsl(var(--body-color));
            font-size: 15px;
            transition: all linear 0.3s;
        }

        .social-login-btn:hover {
            background-color: hsl(var(--base));
            color: #fff;
        }

        .social-login-btn img {
            width: 20px;
        }

        .social-auth {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .auth-devide {
            text-align: center;
            margin-block: 24px;
            position: relative;
            z-index: 1;
        }
    </style>
@endpush
