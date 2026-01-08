@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card table-dropdown-solved b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--lg table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Min Spend')</th>
                                    <th>@lang('Max Spend')</th>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Discount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($coupons as $coupon)
                                    <tr>
                                        <td>{{ __($coupon->name) }}</td>
                                        <td>{{ showAmount($coupon->minimum_spend) }}</td>
                                        <td>{{ showAmount($coupon->maximum_spend) }}</td>
                                        <td>{{ $coupon->code }}</td>
                                        <td>{{ showAmount($coupon->discount_amount, currencyFormat: false) }}
                                            {{ $coupon->discount_type ? '%' : ' ' . __(gs('cur_text')) }}</td>
                                        <td> @php echo $coupon->statusBadge; @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                                    data-coupon="{{ $coupon }}"
                                                    data-amount="{{ getAmount($coupon->discount_amount) }}"
                                                    data-min_spend="{{ getAmount($coupon->minimum_spend) }}"
                                                    data-max_spend="{{ getAmount($coupon->maximum_spend) }}">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($coupon->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-question="@lang('Are you sure to enable this coupon?')"
                                                        data-action="{{ route('admin.coupon.status', $coupon->id) }}">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-question="@lang('Are you sure to disable this coupon?')"
                                                        data-action="{{ route('admin.coupon.status', $coupon->id) }}">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif

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
                @if ($coupons->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($coupons) }}
                    </div>
                @endif
            </div>
        </div>
    </div>


    {{-- Coupon  Modal --}}
    <div id="couponModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Code')</label>
                                    <input type="text" class="form-control" name="code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Discount Type')</label>
                                    <select name="discount_type" class="form-control select2"
                                        data-minimum-results-for-search="-1" required>
                                        <option value="0">@lang('Fixed')</option>
                                        <option value="1">@lang('Percent')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="amount" class="form-control" required>
                                        <span class="input-group-text discountType"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Minimum Spend')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="minimum_spend" class="form-control"
                                            required>
                                        <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Maximum Spend')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="maximum_spend" class="form-control"
                                            required>
                                        <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea name="description" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-outline--primary btn-sm addBtn"><i class="las la-plus"></i>@lang('Add New')</button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            let modal = $('#couponModal');
            let action = `{{ route('admin.coupon.save') }}`;

            $('.addBtn').on('click', function() {
                modal.find('.modal-title').text(`@lang('Add New Coupon')`);
                modal.find('form').attr('action', action);
                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                let data = $(this).data();
                let coupon = data.coupon;
                modal.find('.modal-title').text(`@lang('Update Coupon')`);
                modal.find('[name=name]').val(coupon.name);
                modal.find('[name=code]').val(coupon.code);
                modal.find('[name=discount_type]').val(coupon.discount_type);
                modal.find('[name=amount]').val(data.amount);
                modal.find('[name=minimum_spend]').val(data.min_spend);
                modal.find('[name=maximum_spend]').val(data.max_spend);
                modal.find('[name=description]').val(coupon.description);
                modal.find('form').attr('action', `${action}/${coupon.id}`);
                modal.modal('show');
            });

            modal.on('hidden.bs.modal', function() {
                modal.find('form')[0].reset();
            });

            $('[name=discount_type]').on('change', function() {
                let discountType = $(this).val() * 1;
                $('.discountType').text(discountType ? '%' : `{{ __(gs('cur_text')) }}`);
            }).change();

        })(jQuery);
    </script>
@endpush
