@extends($activeTemplate.'layouts.frontend')
@section('content')
    <main class="main-wrapper">
        <section class="py-60">
            <div class="custom--container">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-center mb-4">{{ __($pageTitle) }}</h5>
                            @php
                                echo $policy->data_values->details;
                            @endphp
                    </div>
                </div>
            </div>
            </div>
        </section>
    </main>
@endsection
