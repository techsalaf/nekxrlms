@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $contact = getContent('contact_us.content', true);
    @endphp
    <main class="main-wrapper contact-page">
        <section class="banner">
            <div class="custom--container">
                <div class="row flex-align">
                    <div class="col-lg-6">
                        <div class="banner__content py-60">
                            <h2 class="banner__title dynamicColor" data-color_word="[4]">{{ __(@$contact->data_values->header) }}</h2>
                            <p class="banner__subtitle fs-18">{{ __(@$contact->data_values->subheader) }}</p>
                        </div>

                    </div>
                    <div class="col-lg-6 d-lg-block d-none">
                        <div class="banner__img">
                            <img class="thumb" src="{{ frontendImage('contact_us', @$contact->data_values->image, '460x390') }}" alt="Banner Image">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-section py-120">
            <div class="custom--container">
                <div class="row">
                    <div class="col-lg-6 contact-section__content">
                        <h4 class="contact-section__title">{{ __(@$contact->data_values->title) }}</h4>
                        <p class="contact-section__desc">{{ __(@$contact->data_values->subtitle) }}</p>
                    </div>
                </div>
                <div class="row gy-5 gx-md-4">
                    <div class="col-md-6">
                        <form class="contact-form verify-gcaptcha" method="POST">
                            @csrf
                            <div class="form-group">
                                <input class="form--control" type="text" name="name" placeholder="@lang('Name')" value="{{ old('name', @$user->fullname) }}" @if ($user) readonly @endif required>
                            </div>
                            <div class="form-group">
                                <input class="form--control" type="email" name="email" placeholder="@lang('Email')" value="{{ old('email', @$user->email) }}" @if ($user) readonly @endif required>
                            </div>
                            <div class="form-group">
                                <input class="form--control" type="text" name="subject" placeholder="@lang('Subject')" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form--control" name="message" placeholder="@lang('Type your message...')"></textarea>
                            </div>
                            <x-captcha :label="false" />

                            <button class="btn btn--base rounded-pill w-100" type="submit">@lang('Submit')</button>
                        </form>
                    </div>
                    <div class="col-md-6 ps-lg-5 ps-md-0">
                        <div class="address-card">
                            <span class="address-card__icon flex-center">
                                <i class="las la-map-marked"></i>
                            </span>
                            <div class="address-card__content">
                                <h5 class="address-card__title">@lang('Office Address')</h5>
                                <p class="address-card__desc">{{ __(@$contact->data_values->contact_details) }}</p>

                            </div>
                        </div>
                        <div class="address-card">
                            <span class="address-card__icon flex-center">
                                <i class="lar la-envelope"></i>
                            </span>
                            <div class="address-card__content">
                                <h5 class="address-card__title">@lang('Email us')</h5>
                                <p class="address-card__desc">{{ __(@$contact->data_values->email_instruction) }}</p>
                                <a href="mailto:{{ @$contact->data_values->email_address }}" class="address-card__link">{{ @$contact->data_values->email_address }}</a>
                            </div>
                        </div>
                        <div class="address-card">
                            <span class="address-card__icon flex-center">
                                <i class="las la-phone"></i>
                            </span>
                            <div class="address-card__content">
                                <h5 class="address-card__title">@lang('Call us')</h5>
                                <p class="address-card__desc">{{ __(@$contact->data_values->mobile_instruction) }}</p>
                                <a href="tel:{{ $contact->data_values->contact_number }}" class="address-card__link">{{ $contact->data_values->contact_number }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
