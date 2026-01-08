@php
    $choose = getContent('choose_us.content', true);
    $chooses = getContent('choose_us.element', orderById: true);
@endphp
<section class="why-join py-120">
    <div class="custom--container">
        <div class="row g-4">
            <div class="col-lg-5 col-xxl-6">
                <div class="why-join-content">
                    <h3 class="why-join-title dynamicColor" data-color_word="[6]">{{ __(@$choose->data_values->title) }}</h3>

                    <span class="why-join-subtitle">
                        {{ __(@$choose->data_values->subtitle) }}
                    </span>

                    <p class="why-join-description">
                        {{ __(@$choose->data_values->description) }}
                    </p>
                </div>
            </div>

            <div class="col-lg-7 col-xxl-6">
                <div class="row g-4">
                    @foreach ($chooses as $choose)
                        <div class="col-sm-6">
                            <div class="why-join-reason">
                                <img class="why-join-reason-icon" src="{{ frontendImage('choose_us', @$choose->data_values->image, '35x35') }}" alt="Verified Badge">

                                <div class="why-join-reason-content">
                                    <h6 class="why-join-reason-title">
                                        {{ __(@$choose->data_values->title) }}
                                    </h6>

                                    <p class="why-join-reason-description">
                                        {{ __(@$choose->data_values->subtitle) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
