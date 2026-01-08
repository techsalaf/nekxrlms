@foreach ($reviews as $review)
    <div class="mb-3 py-3 border-bottom">
        <div class="review-card-ratings">
            @php
                echo ratingStar($review->rating);
            @endphp
        </div>
        <small class="text-muted mb-3">{{ $review->user->fullname }}</small>
        <p class="sm-text">
            {{ __($review->review) }}
        </p>
    </div>
@endforeach

@if ($reviews->count() && $review->id != $firstReview->id)
    <button class="load-more btn btn-outline--base" data-last_id="{{ $review->id }}">@lang('Load More')<span class="icon loading d-none"><i class="las la-spinner"></i></span> </button>
@endif
