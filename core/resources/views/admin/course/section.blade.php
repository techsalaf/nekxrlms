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
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Total Lesson')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sections as $section)
                                    <tr>
                                        <td>{{ $sections->firstItem() + $loop->index }}</td>
                                        <td>{{ __($section->title) }}</td>
                                        <td>{{ $section->lessons_count }}</td>
                                        <td> @php echo $section->statusBadge; @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                                    data-section="{{ $section }}">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                <a href="{{ route('admin.course.section.lessons', $section->id) }}"
                                                    class="btn btn-sm btn-outline--info" data-section="{{ $section }}">
                                                    <i class="la la-book"></i>@lang('Lesson')
                                                </a>
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
                @if ($sections->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($sections) }}
                    </div>
                @endif
            </div>
        </div>
    </div>


    <div id="sectionModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Title')</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="form-group statusBadge">
                            <label>@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"
                                name="status">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-outline--primary btn-sm addBtn"><i class="las la-plus"></i>@lang('Add New')</button>
    <x-back route="{{ route('admin.course.all') }}" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            let modal = $('#sectionModal');
            let action = `{{ route('admin.course.section.save') }}`;

            $('.addBtn').on('click', function() {
                modal.find('.modal-title').text(`@lang('Add Section')`);
                modal.find('form').attr('action', action);
                modal.find('[name=title]').val('');
                modal.find('.statusBadge').hide();
                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                let section = $(this).data('section');
                modal.find('.modal-title').text(`@lang('Update Section')`);
                modal.find('[name=title]').val(section.title);
                modal.find('.statusBadge').show();

                if (section.status == 1) {
                    modal.find('[name=status]').bootstrapToggle('on');
                } else {
                    modal.find('[name=status]').bootstrapToggle('off');
                }
                modal.find('form').attr('action', `${action}/${section.id}`);
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
