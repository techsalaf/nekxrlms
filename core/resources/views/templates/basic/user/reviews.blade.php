@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="dashboard-body">
        @if ($myReviews->count())
            <div class="card p-0">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('Course')</th>
                                    <th>@lang('Rating')</th>
                                    <th>@lang('Review')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($myReviews as $review)
                                    <tr>
                                        <td>
                                            {{ $myReviews->firstItem() + $loop->index }}
                                        </td>
                                        <td>
                                            <a class="text--base" href="{{ route('course.details', [slug($review->course->title), $review->course->id]) }}" class="text--primary">{{ __($review->course->title) }}</a>
                                        </td>
                                        <td>{{ $review->rating }}</td>
                                        <td>{{ __(strLimit($review->review, 50)) }}</td>
                                        <td>
                                            <button class="btn btn--base btn--sm reviewBtn" data-course_id="{{ $review->course_id }}" data-rating="{{ $review->rating }}" data-review="{{ $review->review }}"><i class="las la-star"></i> @lang('Update Review')</button><i class=""></i>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @if ($myReviews->hasPages())
                <div class="card-footer">
                    {{ $myReviews->links() }}
                </div>
            @endif
        </div>
        @else
            @include($activeTemplate . 'partials.data_not_found', ['data' => 'No Reviews found!'])
        @endif
    </div>

    <div class="modal fade custom--modal" id="reviewModal" role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Review')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="add-review">
                        <form action="{{ route('user.course.review') }}" method="POST" class="review-form rating row">
                            @csrf
                            <input type="hidden" name="course_id">
                            <div class="rating-choose">
                                <label class="form-label">@lang('Choose your rating') <span class="text--danger">*</span></label>
                                <div class="rating-form-group mb-3">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <label class="star-label">
                                            <input type="radio" name="rating" value="{{ $i }}" />
                                            @for ($j = 1; $j <= $i; $j++)
                                                <span class="icon"><i class="las la-star"></i></span>
                                            @endfor
                                        </label>
                                    @endfor
                                </div>
                                <div class="mb-4 rating-choose">
                                    <label class="form-label">@lang('Say Something about this course') <span class="text--danger">*</span></label>
                                    <textarea placeholder="Write here about your meal" name="review" rows="5" class="form-control form--control" required></textarea>
                                    <span class="d-flex mt-1 justify-content-end"></span>
                                </div>
                                <div class="review-form-group mb-20 col-12 d-flex flex-wrap">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.reviewBtn').on('click', function() {
                var modal = $('#reviewModal');
                let data = $(this).data();
                modal.find('[name=course_id]').val(data.course_id);
                modal.find('[name=review]').val(data.review);
                modal.find(`.star-label:nth-child(${data.rating})`).click();
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
