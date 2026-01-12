@php
    $policyPages = getContent('policy_pages.element', false, null, true);
    $socialIcons = getContent('social_icon.element', orderById: true);
    $language = App\Models\Language::all();
    $selectedLang = $language->where('code', session('lang'))->first();

@endphp

<footer class="footer py-100">
    <div class="custom--container">
        <!-- Footer Top Section -->
        <div class="footer-top">
            <div class="footer-top-left">
                <a class="footer-logo" href="{{ route('home') }}">
                    <img src="{{ siteLogo() }}" alt="@lang('Logo')">
                </a>
                <p class="footer-tagline">@lang('Empowering learners worldwide with quality education and knowledge sharing')</p>
            </div>

            <div class="footer-links-group">
                <div class="footer-column">
                    <h4 class="footer-column-title">@lang('Quick Links')</h4>
                    <ul class="footer-menu">
                        <li class="footer-menu-item">
                            <a class="footer-menu-link" href="{{ route('home') }}">@lang('Home')</a>
                        </li>
                        <li class="footer-menu-item">
                            <a class="footer-menu-link" href="{{ route('courses') }}">@lang('Courses')</a>
                        </li>
                        <li class="footer-menu-item">
                            <a class="footer-menu-link" href="{{ route('contact') }}">@lang('Contact')</a>
                        </li>
                        @guest
                            <li class="footer-menu-item">
                                <a class="footer-menu-link" href="{{ route('user.login') }}">@lang('Login')</a>
                            </li>
                        @endguest
                    </ul>
                </div>

                @if ($policyPages->count())
                    <div class="footer-column">
                        <h4 class="footer-column-title">@lang('Legal')</h4>
                        <ul class="footer-menu">
                            @foreach ($policyPages as $policy)
                                <li class="footer-menu-item">
                                    <a class="footer-menu-link" href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">
                                        {{ __(@$policy->data_values->title) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (gs()->multi_language)
                    <div class="footer-column">
                        <h4 class="footer-column-title">@lang('Language')</h4>
                        <div class="dropdown-lang dropdown mt-0 d-block footer-lang">
                            <a href="#" class="language-btn dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img class="flag"
                                    src="{{ getImage(getFilePath('language') . '/' . @$selectedLang->image, getFileSize('language')) }}"
                                    alt="language">
                                <span class="language-text">{{ @$selectedLang->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($language as $lang)
                                    <li><a href="{{ route('lang', $lang->code) }}"><img class="flag"
                                                src="{{ getImage(getFilePath('language') . '/' . @$lang->image, getFileSize('language')) }}"
                                                alt="@lang('image')">
                                        {{ @$lang->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <div class="footer-top-right">
                <h4 class="footer-column-title">@lang('Follow Us')</h4>
                <ul class="footer-social">
                    @foreach ($socialIcons as $socialIcon)
                        <li class="footer-social-item">
                            <a class="footer-social-link" href="{{ $socialIcon->data_values->url }}" target="_blank" title="Follow">
                                @php echo $socialIcon->data_values->social_icon @endphp
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Footer Bottom Section -->
        <div class="footer-bottom">
            <p class="footer-rights-text">
                &copy; {{ date('Y') }} <a class="text--base" href="{{ route('home') }}">{{ __(gs()->site_name) }}</a>. @lang('All Rights Reserved'). <span class="footer-credit">@lang('Crafted with') <i class="fas fa-heart"></i> @lang('for learning excellence')</span>
            </p>
        </div>
    </div>
</footer>

