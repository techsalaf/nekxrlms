@php
    $banner = getContent('banner.content', true);
    $studentImages = getContent('banner.element', orderById: true);
    $bannerCategories = App\Models\Category::showOnBanner()->get();
@endphp
<main class="main-wrapper">
    <section class="banner py-120">
        <div class="custom--container">
            <div class="row g-4">
                <div class="col-lg-7 col-xl-7">
                    <h1 class="banner-title dynamicColor" data-color_word="[6]">{{ __(@$banner->data_values->heading) }}</h1>

                    <p class="banner-subtitle">{{ __(@$banner->data_values->subheading) }}</p>

                    <div class="banner-inner">
                        <a class="btn btn--base btn--rounded banner-btn-join" href="{{ $banner->data_values->button_link }}">
                            <span class="text">{{ __($banner->data_values->button) }}</span>
                            <span class="icon"><i class="fas fa-arrow-right"></i></span>
                        </a>

                        <div class="banner-info">
                            <div class="banner-info-student-images">
                                <img src="{{ frontendImage('banner', @$banner->data_values->students_image, '150x45') }}" alt="@lang('Student')">
                            </div>

                            <p class="banner-info-text dynamicColor" data-color_word="[4]">{{ __(@$banner->data_values->subtitle) }}</p>
                        </div>
                    </div>

                    <ul class="banner-keywords">
                        @foreach ($bannerCategories as $bannerCategory)
                            <li class="banner-keywords-item">
                                <a class="banner-keywords-link" href="{{ route('category.course', [slug($bannerCategory->name), $bannerCategory->id]) }}">{{ __(@$bannerCategory->name) }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-5 col-xl-5 position-relative d-none d-lg-flex flex-column justify-content-center align-items-start">
                    <img class="banner-img" src="{{ frontendImage('banner', @$banner->data_values->banner_image, '510x510') }}" alt="Banner Image">

                    <div class="banner-floating-cards">
                        <div class="floating-card">
                            <div class="floating-card-icon">
                                <img src="{{ frontendImage('banner', @$banner->data_values->first_course_image, '40x40') }}" alt="Close Tag">
                            </div>
                            <div class="floating-card-content">
                                <h6 class="floating-card-title">{{ __(@$banner->data_values->first_course_title) }}</h6>
                                <ul class="floating-card-info">
                                    <li class="floating-card-info-item">
                                        <span>{{ __(@$banner->data_values->first_course_price) }}</span>
                                    </li>
                                    <li class="floating-card-info-item">
                                        <span>{{ __(@$banner->data_values->first_course_lesson) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="floating-card">
                            <div class="floating-card-icon">
                                <img src="{{ frontendImage('banner', @$banner->data_values->second_course_image, '40x40') }}" alt="Close Tag">
                            </div>
                            <div class="floating-card-content">
                                <h6 class="floating-card-title">{{ __(@$banner->data_values->second_course_title) }}</h6>

                                <ul class="floating-card-info">
                                    <li class="floating-card-info-item">
                                        <span>{{ __(@$banner->data_values->second_course_price) }}</span>
                                    </li>
                                    <li class="floating-card-info-item">
                                        <span>{{ __(@$banner->data_values->second_course_lesson) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
