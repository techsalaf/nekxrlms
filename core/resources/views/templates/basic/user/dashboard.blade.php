@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="dashboard-body">
        <div class="notice"></div>
        <div class="row gy-4 dashboard-card-wrapper">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="dashboard-card">
                    <div class="dashboard-card__content">
                        <h4 class="dashboard-card__content__number">{{ $widget['total_purchased'] < 10 ? '0' . $widget['total_purchased'] : $widget['total_purchased'] }}</h4>
                        <span>@lang('Course Purchased')</span>
                    </div>
                    <div class="dashboard-card__right">
                        <div class="dashboard-card__icon">
                            <div class="dropdown">
                                <span id="dropdownMenuLink" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </span>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <li><a class="dropdown-item" href="{{ route('user.course.list') }}">@lang('View Course')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('courses') }}">@lang('Purchase Course')</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="dashboard-card">
                    <div class="dashboard-card__content">
                        <h4 class="dashboard-card__content__number">{{ $widget['total_review'] < 10 ? '0' . $widget['total_review'] : $widget['total_review'] }}</h4>
                        <span>@lang('Total Review')</span>
                    </div>
                    <div class="dashboard-card__right">
                        <div class="dashboard-card__icon">
                            <div class="dropdown">
                                <span id="dropdownMenuLink" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </span>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <li><a class="dropdown-item" href="{{ route('user.review') }}">@lang('My Review')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.course.list') }}">@lang('Review Now')</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                <div class="dashboard-card">
                    <div class="dashboard-card__content">
                        <h4 class="dashboard-card__content__number">{{ $widget['total_support_ticket'] < 10 ? '0' . $widget['total_support_ticket'] : $widget['total_support_ticket'] }}</h4>
                        <span>@lang('Support Ticket')</span>
                    </div>
                    <div class="dashboard-card__right">
                        <div class="dashboard-card__icon">
                            <div class="dropdown">
                                <span id="dropdownMenuLink" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </span>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <li><a class="dropdown-item" href="{{ route('ticket.index') }}">@lang('My Ticket')</a></li>
                                    <li><a class="dropdown-item" href="{{ route('ticket.open') }}">@lang('Open New Ticket')</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Dashboard Card End -->

        <div class="mt-5 bg-white p-0">
            @if ($myCourses->count())
                <div class="course-list">
                    @foreach ($myCourses as $myCourse)
                        @include($activeTemplate . 'user.course.item')
                    @endforeach
                </div>
            @else
                @include($activeTemplate . 'partials.data_not_found', ['data' => 'No purchased course found!'])
            @endif
        </div>
    </div>
@endsection
