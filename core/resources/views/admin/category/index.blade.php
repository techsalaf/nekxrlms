@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive table-solved">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Description')</th>
                                    <th>@lang('Featured')</th>
                                    <th>@lang('Show On Banner')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr>
                                        <td>
                                            <div class="user">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('category') . '/' . $category->image, getFileSize('category')) }}"
                                                        alt="{{ __($category->name) }}" class="plugin_bg">
                                                </div>
                                                <span class="name">
                                                    <div>
                                                        <span class="fw-bold">{{ __($category->name) }}</span>
                                                    </div>
                                                </span>
                                            </div>
                                        </td>
                                        <td>{{ __(strLimit($category->description, 30)) }}</td>
                                        <td> @php echo $category->featuredBadge; @endphp </td>
                                        <td> @php echo $category->showOnBannerBadge; @endphp </td>
                                        <td> @php echo $category->statusBadge; @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--primary ms-1 editBtn"
                                                    data-action="{{ route('admin.category.save', $category->id) }}"
                                                    data-name="{{ $category->name }}"
                                                    data-description="{{ $category->description }}"
                                                    data-image="{{ getImage(getFilePath('category') . '/' . $category->image) }}">
                                                    <i class="la la-pencil"></i> @lang('Edit')
                                                </button>

                                                <button type="button" class="btn btn-sm btn-outline--info"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="las la-ellipsis-v"></i>@lang('More')
                                                </button>

                                                <div class="dropdown-menu more-dropdown">
                                                    @if ($category->status == Status::DISABLE)
                                                        <button class="dropdown-item confirmationBtn"
                                                            data-action="{{ route('admin.category.status', $category->id) }}"
                                                            data-question="@lang('Are you sure to enable this category?')">
                                                            <i class="la la-eye"></i> @lang('Enable')
                                                        </button>
                                                    @else
                                                        <button class="dropdown-item confirmationBtn"
                                                            data-action="{{ route('admin.category.status', $category->id) }}"
                                                            data-question="@lang('Are you sure to disable this category?')">
                                                            <i class="la la-eye-slash"></i> @lang('Disable')
                                                        </button>
                                                    @endif

                                                    @if ($category->featured)
                                                        <button class="dropdown-item confirmationBtn"
                                                            data-action="{{ route('admin.category.feature', $category->id) }}"
                                                            data-question="@lang('Are you sure to unfeature this category?')">
                                                            <i class="las la-times"></i> @lang('Make Unfeatured')
                                                        </button>
                                                    @else
                                                        <button class="dropdown-item confirmationBtn"
                                                            data-action="{{ route('admin.category.feature', $category->id) }}"
                                                            data-question="@lang('Are you sure to feature this category?')">
                                                            <i class="las la-check"></i> @lang('Make Featured')
                                                        </button>
                                                    @endif
                                                    @if ($category->show_on_banner)
                                                        <button class="dropdown-item confirmationBtn"
                                                            data-action="{{ route('admin.category.banner', $category->id) }}"
                                                            data-question="@lang('Are you sure to hide this category from banner?')">
                                                            <i class="las la-eye-slash"></i> @lang('Hide from banner')
                                                        </button>
                                                    @else
                                                        <button class="dropdown-item confirmationBtn"
                                                            data-action="{{ route('admin.category.banner', $category->id) }}"
                                                            data-question="@lang('Are you sure to show this category on banner?')">
                                                            <i class="las la-eye"></i> @lang('Show on banner')
                                                        </button>
                                                    @endif
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
                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>


    {{-- CATEGORY  MODAL --}}
    <div id="categoryModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form id="categoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label>@lang('Image')</label>
                                    <x-image-uploader class="w-100" type="category" :required=false />
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea class="form-control" name="description" rows="5" required></textarea>
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
    <x-search-form placeholder="Category Name" />
    <button class="btn btn-outline--primary btn-sm addBtn"><i class="las la-plus"></i>@lang('Add New')</button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            let modal = $('#categoryModal');
            let defaultImage = `{{ getImage(getFilePath('category'), getFileSize('category')) }}`;

            $('.addBtn').on('click', function() {

                modal.find('.modal-title').text(`@lang('Add Category')`);
                modal.find('.image-upload-preview').css('background-image', `url(${defaultImage})`);
                modal.find('form').attr('action', `{{ route('admin.category.save') }}`);
                modal.find('[name=image]').attr('required', true);
                modal.find('.imageLabel').addClass('required');
                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                modal.find('.modal-title').text(`@lang('Updates Category')`);
                let data = $(this).data();
                modal.find('[name=name]').val(data.name);
                modal.find('[name=description]').val(data.description);
                modal.find('.image-upload-preview').css('background-image', `url(${data.image})`);
                modal.find('form').attr('action', $(this).data('action'));
                modal.find('[name=image]').removeAttr('required');
                modal.find('.imageLabel').removeClass('required');
                modal.modal('show');
            });



            modal.on('hidden.bs.modal', function() {
                modal.find('form')[0].reset();
            });

        })(jQuery);
    </script>
@endpush
