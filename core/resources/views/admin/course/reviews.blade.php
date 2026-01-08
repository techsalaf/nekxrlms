@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive table-solved">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Course')</th>
                                    <th>@lang('Rating')</th>

                                    <th>@lang('Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reviews as $review)
                                    <tr>
                                        <td>{{ $reviews->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $review->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a
                                                    href="{{ route('admin.users.detail', $review->user->id) }}"><span>@</span>{{ $review->user->username }}</a>
                                            </span>
                                        </td>
                                        <td>{{ __($review->course->title) }}</td>
                                        <td>{{ $review->rating }}</td>
                                        <td>{{ showDateTime($review->created_at) }}</td>

                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary reviewBtn"
                                                    data-review="{{ __($review->review) }}">
                                                    <i class="la la-eye"></i>@lang('Review')
                                                </button>
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-question="@lang('Are sure to delete this review?')"
                                                    data-action="{{ route('admin.course.review.delete', $review->id) }}">
                                                    <i class="la la-trash"></i>@lang('Delete')
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($reviews->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($reviews) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="reviewModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reviews')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="review"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>


    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Username/Course Title" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.reviewBtn').on('click', function() {
                let modal = $('#reviewModal');
                let review = $(this).data('review');
                modal.find('.review').text(review);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
