<section class="custom--container py-60">
    @if ($premiumCourses->count())
        <div class="py-60">
            <h5 class="courses-type-title">
                @lang('Premium Courses')
            </h5>
            <div class="row g-4">
                @foreach ($premiumCourses as $pCourse)
                    <div class="col-lg-4 col-sm-6">
                        <a class="card course-card h-100"
                            href="{{ route('course.details', [slug($pCourse->title), $pCourse->id]) }}">
                            <div class="card-header">
                                <img class="course-card-thumb"
                                    src="{{ getImage(getFilePath('course') . '/' . $pCourse->image) }}"
                                    alt="{{ __($pCourse->title) }}">
                                <span class="course-card-badge">@lang('Premium')</span>
                            </div>
                            <div class="card-body">
                                <h6 class="course-card-title">{{ __($pCourse->title) }}</h6>
                                <div class="flex-between gap-2">
                                    <span class="course-card-duration">@lang('Duration:')
                                        {{ secondsToHMS($pCourse->total_duration) }}</span>
                                    <div class="review-card-ratings">
                                        @php
                                            echo ratingStar($pCourse->avg_rating);
                                        @endphp
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                @if (purchase($pCourse))
                                    <span class="course-card-price purchased">@lang('Purchased')</span>
                                    <button class="btn btn--rounded btn--success course-card-btn-enroll" type="button">
                                        <i class="fas fa-play-circle"></i>
                                        @lang('Watch')
                                    </button>
                                @else
                                    <span class="course-card-price fs-16">
                                        @if ($pCourse->discount_price > 0)
                                            <del class="text-muted fs-15">{{ showAmount($pCourse->price) }}</del>
                                            {{ showAmount($pCourse->discount_price) }}
                                        @else
                                            {{ showAmount($pCourse->price) }}
                                        @endif
                                    </span>
                                    <button class="btn btn--rounded btn--light course-card-btn-enroll" type="button">
                                        <i class="fas fa-play-circle"></i>
                                        @lang('Enroll now')
                                    </button>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($freeCourses->count())
        <div class="py-60">
            <h5 class="courses-type-title">
                @lang('Free Courses')
            </h5>
            <div class="row g-4">
                @foreach ($freeCourses as $fCourse)
                    <div class="col-lg-4 col-sm-6">
                        <a class="card course-card h-100 price-free"
                            href="{{ route('course.details', [slug($fCourse->title), $fCourse->id]) }}">
                            <div class="card-header">
                                <img class="course-card-thumb"
                                    src="{{ getImage(getFilePath('course') . '/' . $fCourse->image) }}"
                                    alt="{{ __($fCourse->title) }}">
                            </div>
                            <div class="card-body">
                                <h6 class="course-card-title">{{ __($fCourse->title) }}</h6>
                                <div class="flex-between gap-2">
                                    <span class="course-card-duration">
                                        @lang('Duration:') {{ secondsToHMS($fCourse->total_duration) }}
                                    </span>
                                    <div class="review-card-ratings">
                                        @php
                                            echo ratingStar($fCourse->avg_rating);
                                        @endphp
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <span class="course-card-price">{{ gs('cur_sym') }} @lang('Free')</span>
                                <button class="btn btn--rounded btn--success course-card-btn-enroll" type="button">
                                    <i class="fas fa-play-circle"></i>
                                    @lang('Watch')
                                </button>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if (!$premiumCourses->count() && !$freeCourses->count())
        <span class="empty-slip-message text-dark">
            <span class="d-flex justify-content-center align-items-center">
                <img src="{{ getImage('assets/images/empty_list.png') }}" alt="@lang('image')">
            </span>
            <span class="mt-3">@lang('No course found!')</span>
        </span>
    @endif
</section>
