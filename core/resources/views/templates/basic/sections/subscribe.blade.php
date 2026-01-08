@php
    $subscribe = getContent('subscribe.content', true);
@endphp

<section class="subscribe-now pt-60 pb-120">
    <div class="container">
        <div class="subscribe-now-row">
            <div class="subscribe-now-col">
                <div class="subscribe-now-content">
                    <h3 class="subscribe-now-title">{{ __(@$subscribe->data_values->title) }}</h3>

                    <p class="subscribe-now-description">{{ __(@$subscribe->data_values->subtitle) }}</p>

                    <form class="subscribe-now-form" id="subscribe">
                        @csrf
                        <input class="subscribe-now-form-input" type="email" name="email" id="email" placeholder="@lang('Enter your email')" required>
                        <button class="subscribe-now-form-btn btn btn--base" type="submit">@lang('Subscribe')</button>
                    </form>
                </div>
            </div>

            <div class="subscribe-now-col">
                <img class="subscribe-now-thumb" src="{{ frontendImage('subscribe', @$subscribe->data_values->image, '640x550') }}" alt="@lang('Subscribe now')">
            </div>
        </div>
    </div>
</section>

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#subscribe').on('submit', function(e) {
                e.preventDefault();
                var data = $('#subscribe').serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ route('subscribe') }}",
                    data: data,
                    success: function(response) {
                        if (response.status == 'success') {
                            notify('success', response.message);
                            $('#email').val('');
                        } else {
                            notify('error', response.message);
                        }
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
