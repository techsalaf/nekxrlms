@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <main class="main-wrapper">
        <!-- ========== Course Details Start ========== -->
        <div class="course-detail-page">
            <div class="custom--container">
                <div class="course-detail-page-row">
                    <div class="course-detail-page-column course-detail-page-column--content">
                        <h1 class="course-title">{{ __($course->title) }}</h1>

                        <p class="course-description">{{ __($course->short_description) }}</p>


                        @if ($course->premium && $course->lessons->where('premium', 0)->count() > 0)
                            @php
                                $freeLesson = $course->lessons->where('premium', 0)->first();
                            @endphp
                            <div class="course-demo">
                                <div class="course-demo-details">
                                    <img class="icon"
                                        src="{{ asset($activeTemplateTrue . 'images/icons/multiplayer-green.png') }}">
                                    <h6 class="title">@lang('Free demo class')</h6>
                                </div>
                                <a href="{{ route('course.lesson', [slug($freeLesson->title), $freeLesson->id]) }}"
                                    class="course-demo-btn btn btn--rounded" type="button">
                                    <i class="far fa-play-circle"></i>
                                    <span>@lang('Watch the video')</span>
                                </a>
                            </div>
                        @endif

                        @if ($course->learns)
                            <div class="course-wywl">
                                <h5 class="course-wywl-title">@lang('What you\'ll learn:')</h5>
                                <div class="course-wywl-inner">
                                    <div class="course-wywl-content">
                                        <ul class="course-wywl-list">
                                            @foreach ($course->learns ?? [] as $learn)
                                                <li class="course-wywl-list-item">
                                                    <span class="icon"><i class="fas fa-check"></i></span>
                                                    <p class="text">{{ __($learn) }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <button class="course-wywl-collapse" type="button">
                                        <span class="text">@lang('Show More')</span>
                                        <span class="icon"><i class="fas fa-chevron-down"></i></span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="course-content">
                            <h5 class="title">@lang('Course content')</h5>
                            <ul class="course-lessons">
                                @php
                                    $sectionClass = [
                                        'bg--orange',
                                        'bg--light-blue',
                                        'bg--purple',
                                        'bg--dark-blue',
                                        'bg--pink',
                                    ];
                                @endphp
                                @foreach ($course->sections as $section)
                                    <li class="course-lesson has-lesson">
                                        <div class="course-lesson-header" data-bs-toggle="collapse"
                                            data-bs-target="#section-{{ $section->id }}" aria-expanded="false">
                                            <div class="course-lesson-number {{ $sectionClass[$loop->index % 5] }}">
                                                <p>@lang('Section') <br> <span>{{ $loop->iteration }}</span></p>
                                            </div>

                                            <div class="course-lesson-info">
                                                <h5 class="course-lesson-title">{{ __($section->title) }}</h5>

                                                <div class="course-lesson-meta-container">
                                                    <ul class="course-lesson-meta course-lesson-meta--one">
                                                        <li class="course-lesson-meta-item">
                                                            <span class="icon"><i class="las la-video"></i></span>
                                                            <span class="text">{{ $section->lessons->count() }}
                                                                @lang('live classes')</span>
                                                        </li>

                                                        <li class="course-lesson-meta-item">
                                                            <span
                                                                class="text">{{ secondsToHMS($section->lessons->sum('video_duration')) }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="collapse" id="section-{{ $section->id }}">
                                            <div class="course-lesson-body">
                                                <ul class="course-lesson-content">
                                                    @foreach ($section->lessons as $lesson)
                                                        @php
                                                            $lessonUrl = lessonPermission($lesson)
                                                                ? route('course.lesson', [
                                                                    slug($lesson->title),
                                                                    $lesson->id,
                                                                ])
                                                                : 'javascript:void(0)';
                                                        @endphp

                                                        <li class="course-lesson-content-item">
                                                            <a class="course-lesson-content-video-link"
                                                                href="{{ $lessonUrl }}">
                                                                <span class="icon"><i class="las la-camera"></i></span>
                                                                <span class="text">{{ __($lesson->title) }}</span>
                                                                <span
                                                                    class="duration">{{ secondsToHMS($lesson->video_duration) }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @php echo $course->description; @endphp


                        {{-- Rating Review Start --}}
                        <div class="course-reviews mt-5">
                            <div class="course-reviews-box border-bottom-0 py-4">
                                <div class="row g-4 align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-end">
                                            <span class="d-inline-block as-rating">{{ $course->avg_rating }}</span>
                                            <span class="d-inline-block as-rating-divider">/</span>
                                            <span class="d-inline-block as-rating-total">@lang('5')</span>
                                        </div>
                                        <div class="review-card-ratings big-star">
                                            @php
                                                echo ratingStar($course->avg_rating);
                                            @endphp
                                        </div>
                                        <span class="d-inline-block as-rating-text">{{ $course->total_review }}
                                            @lang('Ratings')</span>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="as-ratings">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <li class="as-ratings__item">
                                                    <div class="review-card-ratings">
                                                        @php
                                                            echo ratingStar($i);
                                                            $rating = $course->reviews->where('rating', $i)->count();
                                                            $ratingRatio = $rating
                                                                ? ($rating / $course->total_review) * 100
                                                                : 0;
                                                        @endphp
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress rounded-0 flex-shrink-0 flex-grow-1 me-2">
                                                            <div class="progress-bar customWidth" role="progressbar"
                                                                data-custom_width="{{ $ratingRatio }}" aria-valuenow="90"
                                                                aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="d-inline-block as-rating-text mt-0">
                                                            {{ $rating }}</span>
                                                    </div>
                                                </li>
                                            @endfor
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- course-reviews-box end -->
                            @if ($course->reviews->count())
                                <div class="py-2 border-bottom border-top">
                                    <small>@lang('Students Review')</small>
                                </div>
                                <div class="review">

                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="course-detail-page-column course-detail-page-column--sidebar">
                        <aside class="course-sidebar">
                            <div class="course-sidebar-header">
                                <img class="course-thumb"
                                    src="{{ getImage(getFilePath('course') . '/' . $course->image, getFileSize('course')) }}"
                                    alt="@lang('course')">
                            </div>

                            <div class="course-sidebar-body">
                                @php
                                    $firstLesson = @$course->lessons->first();
                                @endphp
                                @if ($course->premium)
                                    <div class="course-sidebar-box course-sidebar-box--two">
                                        <div class="course-details">
                                            <div class="course-details-wrapper">
                                                <h4 class="course-price">
                                                    @if ($course->discount_price > 0)
                                                        <small><del
                                                                class="text-muted fs-18">{{ showAmount($course->price) }}</del></small>
                                                        {{ showAmount($course->discount_price) }}
                                                    @else
                                                        {{ showAmount($course->price) }}
                                                    @endif
                                                </h4>

                                                @if (!purchase($course))
                                                    <button class="course-promo-apply-btn" type="button">
                                                        <span class="icon"><i class="las la-ticket-alt"></i></span>
                                                        <span class="text">@lang('Promo Code')</span>
                                                    </button>
                                                @endif

                                                <button class="course-share-btn shareBtn" type="button">
                                                    <span class="icon"><i class="las la-share"></i></span>
                                                    <span class="text">@lang('Share')</span>
                                                </button>
                                            </div>

                                            <div class="course-cupon-form d-none">
                                                <button type="button" class="course-cupon-form-close">
                                                    <i class="far fa-times-circle"></i>
                                                </button>
                                                <input class="course-cupon-form-input couponCode" type="text"
                                                    placeholder="@lang('Promo code')">
                                                <button type="button"
                                                    class="btn btn--rounded btn--base applyCoupon">@lang('Apply')</button>
                                            </div>
                                            <span class="couponResponse mt-2"></span>

                                            @if (lessonPermission($firstLesson))
                                                @if (purchase($course))
                                                    <a href="{{ route('course.lesson', [slug(@$firstLesson->title), @$firstLesson->id]) }}"
                                                        class="btn btn--rounded btn--base w-100 course-join-btn">
                                                        <span class="text">@lang('Watch Now')</span>
                                                    </a>
                                                @else
                                                    <button class="btn btn--rounded btn--base w-100 course-join-btn payBtn"
                                                        role="button">
                                                        <span class="text">@lang('Pay') <span
                                                                class="payableAmount">{{ $course->discount_price > 0 ? showAmount($course->discount_price) : showAmount($course->price) }}</span></span>
                                                    </button>
                                                @endif
                                            @else
                                                <button class="btn btn--rounded btn--base w-100 course-join-btn payBtn"
                                                    role="button">
                                                    <span class="text">@lang('Pay') <span
                                                            class="payableAmount">{{ $course->discount_price > 0 ? showAmount($course->discount_price) : showAmount($course->price) }}</span></span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="course-sidebar-box course-sidebar-box--two">
                                        <div class="course-details-wrapper d-flex justify-content-center">
                                            <button class="course-share-btn shareBtn" type="button">
                                                <span class="icon"><i class="las la-share"></i></span>
                                                <span class="text">@lang('Share')</span>
                                            </button>
                                        </div>
                                        <div class="course-sidebar-box course-sidebar-box--two">
                                            <div class="course-details">
                                                <a class="btn btn--rounded btn--base w-100 course-join-btn mt-0"
                                                    href="{{ route('course.lesson', [slug(@$firstLesson->title), @$firstLesson->id]) }}">
                                                    <span class="text">@lang('Watch Now')</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="course-sidebar-box course-sidebar-box--three">
                                    <h5 class="title">@lang('This course includes:')</h5>
                                    <ul class="course-includes">
                                        @foreach ($course->includes['icon'] ?? [] as $icon)
                                            <li class="course-includes-item">
                                                <span>@php echo $icon; @endphp</span>
                                                <span
                                                    class="text">{{ __($course->includes['text'][$loop->index]) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
        <!-- ========== Course Details End =========== -->
    </main>

    <div id="shareModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Course Share')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>

                <div class="modal-body custom-modal__form share">
                    <a class="share-link facebook"
                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                        title="@lang('Facebook')" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>

                    <a class="share-link pinterest"
                        href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ __($course->title) }}&media={{ getImage(getFilePath('course') . '/' . $course->image, getFileSize('course')) }}"
                        title="@lang('Pinterest')" target="_blank">
                        <i class="fab fa-pinterest-p"></i>
                    </a>

                    <a class="share-link linkedin"
                        href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $course->title }}&amp;summary={{ $course->short_description }}"
                        title="@lang('Linkedin')" target="_blank">
                        <i class="fab fa-linkedin-in"></i>
                    </a>

                    <a class="share-link twitter"
                        href="https://twitter.com/intent/tweet?text={{ __($course->title) }}%0A{{ url()->current() }}"
                        title="@lang('Twitter')" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>

                    <div class="copy-link position-relative">
                        <input type="text" class="copyURL form-control form--control" id="copyURL"
                            value="{{ url()->current() }}" readonly="">
                        <span class="copyBoard" id="copyBtn"><i class="las la-copy"></i> <strong
                                class="copyText">@lang('Copy')</strong></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn--sm"
                        data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    <div id="paymentModal" class="modal modal-lg fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Payment')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>

                <form action="{{ route('user.deposit.insert') }}" method="post" class="deposit-form">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <input type="hidden" name="coupon_code">
                    <input type="hidden" name="currency">
                    <div class="gateway-card">
                        <div class="row justify-content-center gy-sm-4 gy-3">
                            <div class="col-lg-6">
                                <div class="payment-system-list is-scrollable gateway-option-list">
                                    @foreach ($gatewayCurrency as $data)
                                        <label for="{{ titleToKey($data->name) }}"
                                            class="payment-item @if ($loop->index > 4) d-none @endif gateway-option">
                                            <div class="payment-item__info">
                                                <span class="payment-item__check"></span>
                                                <span class="payment-item__name">{{ __($data->name) }}</span>
                                            </div>
                                            <div class="payment-item__thumb">
                                                <img class="payment-item__thumb-img"
                                                    src="{{ getImage(getFilePath('gateway') . '/' . $data->method->image) }}"
                                                    alt="@lang('payment-thumb')">
                                            </div>
                                            <input class="payment-item__radio gateway-input"
                                                id="{{ titleToKey($data->name) }}" hidden
                                                data-gateway='@json($data)' type="radio" name="gateway"
                                                value="{{ $data->method_code }}"
                                                @if (old('gateway')) @checked(old('gateway') == $data->method_code) @else @checked($loop->first) @endif
                                                data-min-amount="{{ showAmount($data->min_amount) }}"
                                                data-max-amount="{{ showAmount($data->max_amount) }}">
                                        </label>
                                    @endforeach
                                    @if ($gatewayCurrency->count() > 4)
                                        <button type="button" class="payment-item__btn more-gateway-option">
                                            <p class="payment-item__btn-text">@lang('Show All Payment Options')</p>
                                            <span class="payment-item__btn__icon"><i
                                                    class="fas fa-chevron-down"></i></span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="payment-system-list p-3">
                                    <div class="deposit-info">
                                        <div class="deposit-info__title">
                                            <p class="text mb-0">@lang('Amount')</p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <div class="deposit-info__input-group input-group">
                                                <span class="deposit-info__input-group-text">{{ gs('cur_sym') }}</span>
                                                <input type="text" class="form-control form--control amount"
                                                    name="amount"
                                                    value="{{ $course->discount_price > 0 ? getAmount($course->discount_price) : getAmount($course->price) }}"
                                                    autocomplete="off" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="deposit-info">
                                        <div class="deposit-info__title">
                                            <p class="text has-icon"> @lang('Limit')
                                                <span></span>
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"><span class="gateway-limit">@lang('0.00')</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="deposit-info">
                                        <div class="deposit-info__title">
                                            <p class="text has-icon mb-3">@lang('Processing Charge')
                                                <span data-bs-toggle="tooltip" title="@lang('Processing charge for payment gateways')"
                                                    class="proccessing-fee-info"><i class="las la-info-circle"></i>
                                                </span>
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"><span class="processing-fee">@lang('0.00')</span>
                                                {{ __(gs('cur_text')) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="deposit-info total-amount pt-3">
                                        <div class="deposit-info__title">
                                            <p class="text">@lang('Total')</p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"><span class="final-amount">@lang('0.00')</span>
                                                {{ __(gs('cur_text')) }}</p>
                                        </div>
                                    </div>

                                    <div class="deposit-info gateway-conversion d-none total-amount pt-2">
                                        <div class="deposit-info__title">
                                            <p class="text">@lang('Conversion')
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"></p>
                                        </div>
                                    </div>
                                    <div class="deposit-info conversion-currency d-none total-amount pt-2">
                                        <div class="deposit-info__title">
                                            <p class="text">
                                                @lang('In') <span class="gateway-currency"></span>
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text">
                                                <span class="in-currency"></span>
                                            </p>

                                        </div>
                                    </div>
                                    <div class="d-none crypto-message mb-3">
                                        @lang('Conversion with') <span class="gateway-currency"></span> @lang('and final value will Show on next step')
                                    </div>
                                    <button type="submit" class="btn btn--base w-100 mt-3" disabled>
                                        @lang('Confirm Payment')
                                    </button>
                                    <div class="info-text pt-3">
                                        <p class="text">@lang('Ensuring your funds grow safely through our secure payment process with world-class payment options.')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script>
        (function($) {
            "use strict";
            let coursePrice =
                `{{ getAmount($course->discount_price > 0 ? $course->discount_price : $course->price) }}`;
            $('.applyCoupon').on('click', function() {
                let couponCode = $('.couponCode').val();

                let data = {
                    _token: `{{ csrf_token() }}`,
                    coupon_code: couponCode,
                    course_price: coursePrice
                };

                $.ajax({
                    url: `{{ route('coupon.check') }}`,
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.status == 'error') {
                            let message = '';
                            if (typeof response.message == 'object') {
                                $.each(response.message, function(i, mess) {
                                    message += mess + ' ';
                                });
                            } else {
                                message = response.message;
                            }

                            $('.payableAmount').text(coursePrice);
                            $('[name=amount]').val(coursePrice);
                            $('.couponResponse').removeClass('text--primary').addClass(
                                'text--danger').text(message);
                            $('[name=coupon_code]').val('');
                        } else {
                            $('.payableAmount').text(response.payable_amount);
                            $('[name=amount]').val(response.payable_amount);
                            $('[name=coupon_code]').val(couponCode);
                            $('.couponResponse').addClass('text--primary').removeClass(
                                'text--danger').text(response.message);
                        }
                    }
                })

            });

            $('.shareBtn').on('click', function() {
                let modal = $('#shareModal');

                modal.modal('show');
            });

            $('#copyBtn').on('click', function() {
                var copyText = document.getElementById("copyURL");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(copyText.value);
                notify('success', 'URL copied Successfully');
            });

            $('.payBtn').on('click', function() {
                let modal = $('#paymentModal');
                modal.modal('show');
            });

            var amount = parseFloat($('.amount').val() || 0);
            var gateway, minAmount, maxAmount;


            $('.amount').on('input', function(e) {
                amount = parseFloat($(this).val());
                if (!amount) {
                    amount = 0;
                }
                calculation();
            });

            $('.gateway-input').on('change', function(e) {
                gatewayChange();
            });

            function gatewayChange() {
                let gatewayElement = $('.gateway-input:checked');
                let methodCode = gatewayElement.val();

                gateway = gatewayElement.data('gateway');
                minAmount = gatewayElement.data('min-amount');
                maxAmount = gatewayElement.data('max-amount');

                let processingFeeInfo =
                    `${parseFloat(gateway.percent_charge).toFixed(2)}% with ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }} charge for payment gateway processing fees`
                $(".proccessing-fee-info").attr("data-bs-original-title", processingFeeInfo);
                calculation();
            }

            gatewayChange();

            $(".more-gateway-option").on("click", function(e) {
                let paymentList = $(".gateway-option-list");
                paymentList.find(".gateway-option").removeClass("d-none");
                $(this).addClass('d-none');
                paymentList.animate({
                    scrollTop: (paymentList.height() - 60)
                }, 'slow');
            });

            function calculation() {
                if (!gateway) return;
                $(".gateway-limit").text(minAmount + " - " + maxAmount);

                let percentCharge = 0;
                let fixedCharge = 0;
                let totalPercentCharge = 0;

                if (amount) {
                    percentCharge = parseFloat(gateway.percent_charge);
                    fixedCharge = parseFloat(gateway.fixed_charge);
                    totalPercentCharge = parseFloat(amount / 100 * percentCharge);
                }

                let totalCharge = parseFloat(totalPercentCharge + fixedCharge);
                let totalAmount = parseFloat((amount || 0) + totalPercentCharge + fixedCharge);

                $(".final-amount").text(totalAmount.toFixed(2));
                $(".processing-fee").text(totalCharge.toFixed(2));
                $("input[name=currency]").val(gateway.currency);
                $(".gateway-currency").text(gateway.currency);

                if (amount < Number(gateway.min_amount) || amount > Number(gateway.max_amount)) {
                    $(".deposit-form button[type=submit]").attr('disabled', true);
                } else {
                    $(".deposit-form button[type=submit]").removeAttr('disabled');
                }

                if (gateway.currency != "{{ gs('cur_text') }}" && gateway.method.crypto != 1) {
                    $('.deposit-form').addClass('adjust-height')

                    $(".gateway-conversion, .conversion-currency").removeClass('d-none');
                    $(".gateway-conversion").find('.deposit-info__input .text').html(
                        `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency">${gateway.currency}</span>`
                    );
                    $('.in-currency').text(parseFloat(totalAmount * gateway.rate).toFixed(gateway.method.crypto == 1 ?
                        8 : 2))
                } else {
                    $(".gateway-conversion, .conversion-currency").addClass('d-none');
                    $('.deposit-form').removeClass('adjust-height')
                }

                if (gateway.method.crypto == 1) {
                    $('.crypto-message').removeClass('d-none');
                } else {
                    $('.crypto-message').addClass('d-none');
                }
            }

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
            $('.gateway-input').change();

            $('.customWidth').each(function(element) {
                $(this).css('width', `${$(this).data('custom_width')}%`);
            });

            loadReview();

            var lastId = 0;

            $(document).on('click', '.load-more', function() {
                lastId = $(this).data('last_id');
                $('.loading').removeClass('d-none');
                loadReview();
            });

            function loadReview() {

                let data = {
                    _token: `{{ csrf_token() }}`,
                    course_id: `{{ $course->id }}`,
                    last_id: lastId
                }

                $.ajax({
                    url: `{{ route('course.reviews') }}`,
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.status == 'error') {
                            return false;
                        }
                        if (lastId == 0) {
                            $('.review').html(response);
                        } else {
                            $('.load-more').remove();
                            $('.review').append(response);
                        }
                    }
                });
            }

        })(jQuery);
    </script>
@endpush
