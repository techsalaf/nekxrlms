@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $pageContent = getContent('course_list.content', true);
    @endphp
    <main class="main-wrapper courses-page">
        <section class="banner" style="background: url({{ frontendImage('course_list', @$pageContent->data_values->background_image, '1920x310') }}) no-repeat center center; background-size: cover;">
            <div class="custom--container">
                <div class="row flex-align">
                    <div class="col-md-6">
                        <div class="banner__content py-120">
                            <h4 class="banner__title text-white">
                                <span class="icon"><img src="{{ frontendImage('course_list', @$pageContent->data_values->icon_image, '50x50') }}" alt="icon"></span>
                                {{ __(@$pageContent->data_values->title) }}
                            </h4>
                            <p class="banner__subtitle text-white fs-18">{{ __(@$pageContent->data_values->subtitle) }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="d-flex text-white live-video">
                            <div class="live-video__frame frame-1">
                                <video autoplay loop muted>
                                    <source src="{{ asset(getFilePath('coursePageVideo').'/'.gs('course_video_one')) }}" type="video/mp4">
                                    @lang('Your browser does not support this video please update your browse.')
                                </video>
                            </div>
                            <div class="live-video__frame frame-2 mt-auto">
                                <video autoplay loop muted>
                                    <source src="{{ asset(getFilePath('coursePageVideo').'/'.gs('course_video_two')) }}" type="video/mp4">
                                    @lang('Your browser does not support this video please update your browse.')
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include($activeTemplate . 'partials.courses', ['premiumCourses' => $premiumCourses, 'freeCourses' => $freeCourses])
    </main>

    @if ($sections != null)
        @foreach (json_decode($sections) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
