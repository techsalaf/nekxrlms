@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <main class="main-wrapper">
        <div class="course-view-page">
            <div class="custom--container">
                <div class="row gy-4">
                    <div class="col-12">
                        <div class="course-view-inner course-view-inner-left">
                            <h5 class="title mb-3">{{ __($course->title) }} — {{ __($currentLesson->title) }}</h5>

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

                            @if (auth()->check())
                                @php
                                    $currentLessonState = $lessonStates[$currentLesson->id] ?? ['is_locked' => false, 'is_completed' => false];
                                @endphp
                                <div class="d-flex justify-content-end mt-3">
                                    <button id="markCompleteBtn" class="btn btn--base"
                                        @if ($currentLessonState['is_completed']) disabled @endif>
                                        @if ($currentLessonState['is_completed'])
                                            <i class="las la-check-circle"></i> @lang('Completed')
                                        @else
                                            <i class="las la-check"></i> @lang('Mark as Completed')
                                        @endif
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="course-view-box course-view-box--two">
                            <h5 class="course-view-title">@lang('Lesson Feed')</h5>

                            @foreach ($course->sections as $section)
                                <div class="lesson-section mb-4">
                                    <div class="lesson-section-head">
                                        <h6 class="mb-1">@lang('Section - '){{ $loop->iteration }}: {{ __($section->title) }}</h6>
                                        <small class="text-muted">{{ secondsToHMS($section->lessons->sum('video_duration')) }}</small>
                                    </div>

                                    <div class="lesson-feed-list">
                                        @foreach ($section->lessons as $lesson)
                                            @php
                                                $state = $lessonStates[$lesson->id] ?? ['is_locked' => !lessonPermission($lesson), 'is_completed' => in_array($lesson->id, $completedLessonIdsArray ?? [])];
                                                $isLocked = $state['is_locked'];
                                                $isCompleted = $state['is_completed'];
                                                $isCurrent = $lesson->id == $currentLesson->id;
                                                $lessonUrl = $isLocked
                                                    ? 'javascript:void(0)'
                                                    : route('course.lesson', [slug($lesson->title), $lesson->id]);
                                            @endphp

                                            <a href="{{ $lessonUrl }}"
                                                class="lesson-feed-item {{ $isCurrent ? 'active' : '' }} {{ $isLocked ? 'locked' : '' }}">
                                                <div class="lesson-thumb-wrap">
                                                    <img src="{{ getImage(getFilePath('video_thumb') . '/' . $lesson->thumb_image, getFileSize('video_thumb')) }}"
                                                        alt="{{ __($lesson->title) }}" class="lesson-thumb">
                                                </div>
                                                <div class="lesson-meta-wrap">
                                                    <div class="lesson-title-row">
                                                        <h6 class="lesson-title mb-0">{{ __($lesson->title) }}</h6>
                                                        <span class="lesson-duration">{{ secondsToHMS($lesson->video_duration) }}</span>
                                                    </div>
                                                    <div class="lesson-status-row">
                                                        @if ($isLocked)
                                                            <span class="lesson-status lock"><i class="las la-lock"></i> @lang('Locked')</span>
                                                        @elseif($isCompleted)
                                                            <span class="lesson-status done"><i class="las la-check-circle"></i> @lang('Completed')</span>
                                                        @elseif($isCurrent)
                                                            <span class="lesson-status current"><i class="las la-play-circle"></i> @lang('Now Watching')</span>
                                                        @else
                                                            <span class="lesson-status open"><i class="las la-unlock"></i> @lang('Unlocked')</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12">
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

        .lesson-section-head {
            border-left: 3px solid hsl(var(--base));
            padding-left: 12px;
            margin-bottom: 14px;
        }

        .lesson-feed-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 560px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .lesson-feed-item {
            display: flex;
            align-items: center;
            gap: 14px;
            background: hsl(var(--white));
            border: 1px solid hsl(var(--black)/0.08);
            border-radius: 12px;
            padding: 10px;
            text-decoration: none;
            transition: all .2s;
        }

        .lesson-feed-item:hover {
            border-color: hsl(var(--base)/0.5);
            transform: translateY(-1px);
        }

        .lesson-feed-item.active {
            border-color: hsl(var(--base));
            box-shadow: 0 0 0 2px hsl(var(--base)/0.1);
        }

        .lesson-feed-item.locked {
            opacity: .78;
        }

        .lesson-thumb-wrap {
            width: 160px;
            min-width: 160px;
            height: 90px;
            border-radius: 8px;
            overflow: hidden;
            background: hsl(var(--black)/0.05);
        }

        .lesson-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .lesson-meta-wrap {
            width: 100%;
            min-width: 0;
        }

        .lesson-title-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: center;
        }

        .lesson-title {
            color: hsl(var(--heading-color));
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .lesson-duration {
            color: hsl(var(--body-color));
            font-size: 13px;
            white-space: nowrap;
        }

        .lesson-status-row {
            margin-top: 8px;
        }

        .lesson-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            font-weight: 500;
        }

        .lesson-status.lock {
            color: #dc3545;
        }

        .lesson-status.done {
            color: #198754;
        }

        .lesson-status.current {
            color: hsl(var(--base));
        }

        .lesson-status.open {
            color: #0d6efd;
        }

        @media screen and (max-width: 575px) {
            .lesson-feed-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .lesson-thumb-wrap {
                width: 100%;
                min-width: 100%;
                height: 180px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            @if (auth()->check())
                let completionInProgress = false;

                const markComplete = function() {
                    if (completionInProgress) {
                        return;
                    }

                    const btn = $('#markCompleteBtn');
                    if (!btn.length || btn.prop('disabled')) {
                        return;
                    }

                    completionInProgress = true;
                    btn.attr('disabled', true);

                    $.post(`{{ route('course.lesson.complete', $currentLesson->id) }}`, {
                        _token: '{{ csrf_token() }}'
                    }).done(function(response) {
                        btn.html('<i class="las la-check-circle"></i> @lang('Completed')');
                        if (typeof notify === 'function') {
                            notify('success', response.message || `@lang('Lesson marked as completed')`);
                        }
                        setTimeout(function() {
                            window.location.reload();
                        }, 700);
                    }).fail(function(xhr) {
                        completionInProgress = false;
                        btn.attr('disabled', false);
                        const message = xhr?.responseJSON?.message || `@lang('Something went wrong. Please try again.')`;
                        if (typeof notify === 'function') {
                            notify('error', message);
                        }
                    });
                };

                $('#markCompleteBtn').on('click', function(e) {
                    e.preventDefault();
                    markComplete();
                });

                // Auto-complete local video lessons when playback ends.
                const nativeVideo = document.querySelector('#your-video_html5_api') || document.querySelector('#your-video');
                if (nativeVideo && nativeVideo.tagName && nativeVideo.tagName.toLowerCase() === 'video') {
                    nativeVideo.addEventListener('ended', function() {
                        markComplete();
                    });
                }
            @endif
        })(jQuery);
    </script>
@endpush
