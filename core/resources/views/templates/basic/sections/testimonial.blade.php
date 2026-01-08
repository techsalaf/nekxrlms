@php
    $testimonial = getContent('testimonial.content', true);
    $testimonials = getContent('testimonial.element');
@endphp

<section class="student-reviews py-60">
    <div class="container">

        <h3 class="student-reviews-title">{{ __(@$testimonial->data_values->title) }}</h3>

        <div class="row g-4">
            @foreach ($testimonials as $testimonial)
                <div class="col-md-6 col-lg-4">
                    <div class="review-card">
                        <div class="review-card-ratings">
                            @php
                                echo ratingStar(@$testimonial->data_values->rating);
                            @endphp
                        </div>

                        <p class="review-card-text">{{ __(@$testimonial->data_values->review) }}</p>

                        <div class="review-card-student">
                            <img class="review-card-student-avatar" src="{{ frontendImage('testimonial', $testimonial->data_values->image, '50x50') }}" alt="@lang('image')">

                            <div class="review-card-student-info">
                                <strong class="review-card-student-name">{{ __(@$testimonial->data_values->name) }}</strong>

                                <span class="review-card-student-position">{{ __(@$testimonial->data_values->designation) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
