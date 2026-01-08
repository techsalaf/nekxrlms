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
                                    <th class="text-start">@lang('Title / Category')</th>
                                    <th>@lang('Active Section')</th>
                                    <th>@lang('Active Lesson')</th>
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Total Sold')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($courses as $course)
                                    <tr>
                                        <td>
                                            <div class="user">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('course') . '/' . $course->image, getFileSize('course')) }}"
                                                        alt="{{ __($course->title) }}" class="plugin_bg">
                                                </div>
                                                <span class="name">
                                                    <div>
                                                        <span class="text-primary">{{ __($course->title) }}</span>
                                                        <br>
                                                        {{ __($course->category->name) }}
                                                    </div>
                                                </span>
                                            </div>
                                        </td>
                                        <td>{{ $course->sections_count }}</td>
                                        <td>{{ $course->lessons_count }}</td>
                                        <td>{{ secondsToHMS($course->total_duration) }}</td>
                                        <td>
                                            @if ($course->discount_price > 0)
                                                <del>{{ showAmount($course->price) }}</del>
                                                {{ showAmount($course->discount_price) }}
                                            @else
                                                {{ showAmount($course->price) }}
                                            @endif
                                        </td>
                                        <td> {{ $course->total_sold }} </td>
                                        <td> @php echo $course->statusBadge; @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.course.edit', $course->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>

                                                <button type="button" class="btn btn-sm btn-outline--info"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="las la-ellipsis-v"></i>@lang('More')
                                                </button>

                                                <div class="dropdown-menu more-dropdown">
                                                    @if ($course->status == Status::DISABLE)
                                                        <button class="dropdown-item confirmationBtn"
                                                            data-action="{{ route('admin.course.status', $course->id) }}"
                                                            data-question="@lang('Are you sure to enable this course?')">
                                                            <i class="la la-eye"></i> @lang('Enable')
                                                        </button>
                                                    @else
                                                        <button class="dropdown-item confirmationBtn"
                                                            data-action="{{ route('admin.course.status', $course->id) }}"
                                                            data-question="@lang('Are you sure to disable this course?')">
                                                            <i class="la la-eye-slash"></i> @lang('Disable')
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('admin.course.section', $course->id) }}"
                                                        class="dropdown-item">
                                                        <i class="las la-layer-group"></i> @lang('Sections')
                                                    </a>
                                                    <a href="{{ route('admin.course.lessons', $course->id) }}"
                                                        class="dropdown-item">
                                                        <i class="las la-video"></i> @lang('Lessons')
                                                    </a>
                                                </div>
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
                @if ($courses->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($courses) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Course Name" />
    @php $route = request()->routeIs('admin.course.free') ? route('admin.course.add').'?free' : route('admin.course.add') @endphp
    <a href="{{ $route }}" class="btn btn-outline--primary btn-sm"><i class="las la-plus"></i>@lang('Add New')</a>
@endpush
