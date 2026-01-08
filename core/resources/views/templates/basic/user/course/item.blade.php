@php $course = $myCourse->course; @endphp

<div class="course-wrapper">

</div>
<div class="course">
    <div class="course__thumb">
        <img src="{{ getImage(getFilePath('course') . '/' . $course->image, getFileSize('course')) }}" alt="@lang('course-preview')" class="fit-image ">
    </div>
    <div class="course__content">
        <div class="course__title">
            <h3 class="course__name">{{ __($course->title) }}</h3>
            <div class="flex-align">
                <div class="review-card-ratings">
                    @php
                        echo ratingStar($course->avg_rating);
                    @endphp
                </div>
                <span class="course__ratting number ms-1"> {{ showAmount($course->avg_rating, 1, currencyFormat:false) }} ({{ $course->total_review }})</span>
            </div>
        </div>
        <div class="course__details">
            <div class="feature-list">
                <div class="inner-list">
                    <span class="feature-list__name">@lang('Total Lesson:')</span>
                    <span class="feature-list__value">{{ $course->lessons_count }}</span>
                </div>
                <div class="inner-list">
                    <span class="feature-list__name">@lang('Duration:')</span>
                    <span class="feature-list__value">{{ durationTimeFormat($course->total_duration) }}</span>
                </div>
            </div>
            <div class="feature-list">
                <div class="inner-list">
                    <span class="feature-list__name">@lang('Price:')</span>
                    <span class="feature-list__value course__price">{{ showAmount($myCourse->purchased_amount) }}</span>
                </div>

                <div class="inner-list">
                    <span class="feature-list__name">@lang('Purchased Date:')</span>
                    <span class="feature-list__value">{{ showDateTime($myCourse->created_at, 'd M Y') }}</span>
                </div>
            </div>
            <div class="value"></div>
        </div>
    </div>
    <div class="course__btn text-end">
        <a href="{{ route('course.details', [slug($course->title), $course->id]) }}" class="btn btn--base"> @lang('View Course') <i class="fas fa-arrow-right"></i></a>

        @if (in_array($course->id, $myReviews))
            <a href="{{ route('user.review') }}" class="rate d-block text-decoration-underline">@lang('Update rating')</a>
        @else
            @php
                $firstLesson = $course->lessons->first();
            @endphp
            <a href="{{ route('course.lesson', [slug(@$firstLesson->title), @$firstLesson->id]) }}" class="rate d-block text-decoration-underline">@lang('Rate this Course')</a>
        @endif

    </div>
</div>
