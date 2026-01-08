@php
    $courseInfo = getContent('course_info.content', true);
    $courseInfos = getContent('course_info.element', orderById: true);
@endphp

<section class="live-course-info pt-120 pb-60">
    <div class="custom--container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="live-course-info-content">
                    <h3 class="live-course-info-title">{{ __($courseInfo->data_values->title) }}</h3>
                    <p class="live-course-info-description">{{ __($courseInfo->data_values->subtitle) }}</p>
                    <a class="live-course-info-lightbox" href="{{ $courseInfo->data_values->video_link }}">
                        <img src="{{ frontendImage('course_info', $courseInfo->data_values->video_thumb,'500x330') }}" alt="Live Course Info Lightbox Thumb">
                    </a>
                </div>
            </div>

            <div class="col-md-6">
                <ul class="live-course-info-keypoints">
                    @foreach ($courseInfos as $courseInfo)
                    <li class="live-course-info-keypoints-item">
                        <div class="live-course-info-keypoints-icon">
                            <img src="{{ frontendImage('course_info', @$courseInfo->data_values->image,'50x50') }}" alt="@lang('Course Info')">
                        </div>

                        <div class="live-course-info-keypoints-content">
                            <h6 class="live-course-info-keypoints-title">
                                {{ __(@$courseInfo->data_values->title) }}
                            </h6>

                            <p class="live-course-info-keypoints-description">
                                {{ __(@$courseInfo->data_values->subtitle) }}
                            </p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
