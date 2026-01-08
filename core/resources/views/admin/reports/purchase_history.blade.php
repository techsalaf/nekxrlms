@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">

                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Course')</th>
                                    <th>@lang('Purchased Amount')</th>
                                    <th>@lang('Coupon')</th>
                                    <th>@lang('Date Time')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coursePurchases as $coursePurchase)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ @$coursePurchase->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $coursePurchase->user_id) }}">
                                                    <span>@</span>{{ @$coursePurchase->user->username }}
                                                </a>
                                            </span>
                                        </td>
                                        <td>{{ __($coursePurchase->course->title) }}</td>
                                        <td>
                                            <span class="text-primary fw-bold">
                                                {{ showAmount($coursePurchase->purchased_amount) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($coursePurchase->coupon_amount > 0)
                                                <span class="text-primary fw-bold">
                                                    {{ showAmount($coursePurchase->coupon_amount) }}
                                                </span>
                                            @else
                                                ---
                                            @endif
                                        </td>
                                        <td>
                                            {{ showDateTime($coursePurchase->created_at) }}
                                            <br>
                                            {{ diffForHumans($coursePurchase->created_at) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($coursePurchases->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($coursePurchases) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    @if (!isset(request()->days))
        <x-search-form placeholder="Username/Course Title" dateSearch='yes' />
    @endif
@endpush
