@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <main class="main-wrapper">
        <div class="course-view-page">
            <div class="custom--container">
                <div class="row gy-4 gy-lg-0 gx-lg-4">
                    <div class="col-lg-8">
                        <div class="course-view-inner course-view-inner-left">
                            @if ($currentLesson->server == 2 || $currentLesson->server == 3)
                                <iframe width="100%" height="500px" src="{{ convertToEmbedUrl($currentLesson->path) }}"
                                    frameborder="0" allowfullscreen></iframe>
                            @else
                                <video class="video-js course-view-video" id="your-video" data-setup="{}" controls
                                    preload="auto"
                                    poster="{{ getImage(getFilePath('video_thumb') . '/' . $currentLesson->thumb_image) }}"
                                    controlsList="nodownload">
                                    <source src="{{ getVideoPath($currentLesson) }}" type="video/mp4" />
                                    <p class="vjs-no-js">
                                        @lang('To view this video please enable JavaScript, and consider upgrading to a web browser that')
                                        <a href="https://videojs.com/html5-video-support/"
                                            target="_blank">@lang('supports HTML5 video')</a>
                                    </p>
                                </video>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="course-view-inner course-view-inner-right">
                            <h5 class="title">{{ __($course->title) }}</h5>

                            <div class="accordion" id="course-content-accordion">
                                @foreach ($course->sections as $section)
                                    <div class="accordion-item">
                                        <div class="accordion-header">
                                            <button
                                                class="accordion-button @if ($section->id != $currentLesson->section_id) collapsed @endif"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#course-content-accordion-item-{{ $loop->iteration }}"
                                                type="button"
                                                aria-expanded="{{ $section->id == $currentLesson->section_id ? 'true' : 'false' }}"
                                                aria-controls="course-content-accordion-item-{{ $loop->iteration }}">
                                                <div class="accordion-headings">
                                                    <h5 class="heading">@lang('Section - '){{ $loop->iteration }}:
                                                        {{ __($section->title) }}</h5>
                                                    <span
                                                        class="sub-heading">{{ secondsToHMS($section->lessons->sum('video_duration')) }}</span>
                                                </div>
                                            </button>
                                        </div>

                                        <div class="accordion-collapse @if ($section->id == $currentLesson->section_id) show @endif collapse"
                                            id="course-content-accordion-item-{{ $loop->iteration }}"
                                            data-bs-parent="#course-content-accordion">
                                            <div class="accordion-body">
                                                <ul class="course-lesson-content">
                                                    @foreach ($section->lessons as $lesson)
                                                        @php
                                                            $lessonUrl = lessonPermission($lesson)
                                                                ? route('course.lesson', [
                                                                    slug($lesson->title),
                                                                    $lesson->id,
                                                                ])
                                                                : 'javascript:void(0)';
                                                        @endphp
                                                        <li class="course-lesson-content-item">
                                                            <a class="course-lesson-content-video-link"
                                                                href="{{ $lessonUrl }}">
                                                                @if ($lesson->id != $currentLesson->id)
                                                                    <span class="icon"><i
                                                                            class="far fa-check-circle"></i></span>
                                                                @else
                                                                    <span class="icon scroll-here"><i
                                                                            class="fas fa-check-circle"></i></span>
                                                                @endif
                                                                <span class="text">{{ $loop->iteration }}.
                                                                    {{ __($lesson->title) }}</span>
                                                                <span
                                                                    class="duration">{{ secondsToHMS($lesson->video_duration) }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="course-view-box course-view-box--one">
                            <span class="course-view-subtitle">
                                @lang('Course overview')
                            </span>
                        </div>

                        @if ($currentLesson->description)
                            <div class="course-view-box course-view-box--two">
                                <h5 class="course-view-title">@lang('Lesson Overview')</h5>

                                <p class="course-view-description">
                                    {{ __($currentLesson->description) }}
                                </p>
                            </div>
                        @endif

                        @if ($course->short_description)
                            <div class="course-view-box course-view-box--two">
                                <h5 class="course-view-title">@lang('About this course')</h5>

                                <p class="course-view-description">
                                    {{ __($course->short_description) }}
                                </p>
                            </div>
                        @endif

                        <div class="course-view-box course-view-box--three">
                            <h5 class="title">@lang('This course includes:')</h5>

                            <ul class="course-includes">
                                @foreach ($course->includes['icon'] ?? [] as $icon)
                                    <li class="course-includes-item">
                                        <span>@php echo $icon; @endphp</span>
                                        <span class="text">{{ __($course->includes['text'][$loop->index]) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @if ($currentLesson->asset_path)
                            <div class="course-view-box course-view-box--two">
                                <h5 class="course-view-title">@lang('Download lesson asset')</h5>
                                <a class="download-link" href="{{ route('lesson.asset.download', $currentLesson->id) }}">
                                    <i class="fas fa-download"></i>
                                    <span>@lang('Download Asset File')</span>
                                </a>
                            </div>
                        @endif

                        @if (auth()->check())
                            <div class="course-view-box course-view-box--two">
                                @if ($userReview)
                                    <h5 class="course-view-title">@lang('Update Your Review')</h5>
                                @else
                                    <h5 class="course-view-title">@lang('Add Your Review')</h5>
                                @endif
                                <form action="{{ route('user.course.review') }}" method="POST">
                                    @csrf
                                    <input name="course_id" type="hidden" value="{{ $course->id }}">
                                    <div class="form-group review-form-group col-md-6 d-flex mb-20 mt-3 flex-wrap">
                                        <label class="review-label mb-0 me-3">@lang('Your Rating') :</label>
                                        <div class="rating-form-group">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <label class="star-label">
                                                    <input name="rating" type="radio" value="{{ $i }}"
                                                        {{ $userReview && $userReview->rating == $i ? 'checked' : '' }}>
                                                    @for ($j = 1; $j <= $i; $j++)
                                                        <span class="icon"><i class="las la-star"></i></span>
                                                    @endfor
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form--control" name="review" placeholder="Write Your Review">{{ $userReview ? $userReview->review : '' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn--base w-100" type="submit">@lang('Submit Review')</button>
                                    </div>

                                </form>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('style-lib')
    <link href="{{ asset('assets/global/css/video.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/video.min.js') }}"></script>
@endpush

@push('style')
    <style>
        .course-view-video {
            width: 100%;
            height: 570px;
        }

        .course-view-video .vjs-poster img {
            object-fit: cover;
        }

        .course-view-video .vjs-big-play-button {
            width: 125px;
            height: 125px;
            border: 1px solid hsl(var(--base));
            border-radius: 50%;
            box-shadow: 0px 0px 5px 3px hsl(var(--base));
            background-color: hsl(var(--body-color));
            margin-top: 0px;
            margin-left: 0px;
            transform: translate(-50%, -50%);
        }

        .course-view-video .vjs-big-play-button:focus,
        .course-view-video .vjs-big-play-button:hover {
            border-color: hsl(var(--base));
            background-color: hsl(var(--body-color));
        }

        @media screen and (max-width: 991px) {
            .course-view-video .vjs-big-play-button {
                width: 100px;
                height: 100px;
            }
        }

        @media screen and (max-width: 991px) {
            .course-view-video .vjs-big-play-button {
                width: 70px;
                height: 70px;
            }
        }

        .course-view-video .vjs-big-play-button .vjs-icon-placeholder::before {
            content: "";
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background-image: url({{ asset($activeTemplateTrue . 'images/icons/play.png') }});
            background-size: 50%;
            background-position: center;
            background-repeat: no-repeat;
        }

        .course-view-video:hover .vjs-big-play-button {
            border-color: hsl(var(--base));
            background-color: hsl(var(--body-color));
        }

        @media screen and (max-width: 1199px) {
            .course-view-video {
                height: 500px;
            }
        }

        @media screen and (max-width: 991px) {
            .course-view-video {
                height: 480px;
            }
        }

        @media screen and (max-width: 767px) {
            .course-view-video {
                height: 400px;
            }
        }

        @media screen and (max-width: 575px) {
            .course-view-video {
                height: 350px;
            }
        }

        @media screen and (max-width: 424px) {
            .course-view-video {
                height: 204px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                var scrollHereElement = $(".scroll-here").first();

                if (scrollHereElement.length) {
                    var container = $(".course-view-inner-right");
                    container.animate({
                        scrollTop: scrollHereElement.offset().top - container.offset().top + container
                            .scrollTop()
                    }, 1000);
                }
            });
        })(jQuery);
    </script>
@endpush
