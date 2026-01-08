@php
    $sponsor = getContent('sponsor.content', true);
    $sponsors = getContent('sponsor.element', orderById: true);
@endphp

@if ($sponsors->count())
    <section class="used-by py-60">
        <div class="custom--container">
            <h6 class="used-by-title">{{ __(@$sponsor->data_values->title) }}</h6>
            <div class="used-by-logos">
                @foreach ($sponsors as $sponsor)
                    <img src="{{ frontendImage('sponsor', @$sponsor->data_values->sponsor_image, '150x50') }}" alt="@lang('Image')">
                @endforeach
            </div>
        </div>
    </section>
@endif
