@extends('admin.layouts.app')

@section('panel')
    @php
        $sectionLesson = request()->routeIs('admin.course.section.lessons') ? 1 : 0;
    @endphp
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive table-solved">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th class="text-start">@lang('Title')</th>
                                    @if (!$sectionLesson)
                                        <th>@lang('Section')</th>
                                    @endif
                                    <th>@lang('Video/Asset Server')</th>
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Views')</th>
                                    <th>@lang('Premium')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lessons as $lesson)
                                    <tr>
                                        <td>@lang('Lesson # '){{ $lessons->firstItem() + $loop->index }}</td>
                                        <td class="text-start">{{ __($lesson->title) }}</td>

                                        @if (!$sectionLesson)
                                            <td>
                                                <a
                                                    href="{{ route('admin.course.section', $lesson->section->id) }}">{{ __(strLimit($lesson->section->title)) }}</a>
                                            </td>
                                        @endif
                                        <td>
                                            @if ($lesson->server)
                                                @lang('FTP')
                                            @elseif($lesson->path)
                                                @lang('Current')
                                            @else
                                                --
                                            @endif /
                                            @if ($lesson->asset_server)
                                                @lang('FTP')
                                            @elseif($lesson->asset_path)
                                                @lang('Current')
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>{{ secondsToHMS($lesson->video_duration) }}</td>
                                        <td>{{ $lesson->views }}</td>
                                        <td> @php echo $lesson->premiumBadge; @endphp </td>
                                        <td> @php echo $lesson->statusBadge; @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary editBtn"
                                                    data-lesson="{{ $lesson }}"
                                                    data-price="{{ getAmount($lesson->price) }}"
                                                    data-image="{{ getImage(getFilePath('video_thumb') . '/' . $lesson->thumb_image) }}">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>

                                                <button type="button" class="btn btn-sm btn-outline--info"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="las la-ellipsis-v"></i>@lang('More')
                                                </button>

                                                <div class="dropdown-menu more-dropdown">
                                                    <a href="{{ route('admin.course.lesson.video', $lesson->id) }}"
                                                        class="dropdown-item">
                                                        <i class="la la-video"></i>
                                                        @if ($lesson->path)
                                                            @lang('Update Video')
                                                        @else
                                                            @lang('Upload Video')
                                                        @endif
                                                    </a>

                                                    @if ($lesson->status == Status::DISABLE)
                                                        <button class="dropdown-item confirmationBtn"
                                                            data-action="{{ route('admin.course.lesson.status', $lesson->id) }}"
                                                            data-question="@lang('Are you sure to enable this video?')">
                                                            <i class="la la-eye"></i> @lang('Enable')
                                                        </button>
                                                    @else
                                                        <button class="dropdown-item confirmationBtn"
                                                            data-action="{{ route('admin.course.lesson.status', $lesson->id) }}"
                                                            data-question="@lang('Are you sure to disable this video?')">
                                                            <i class="la la-eye-slash"></i> @lang('Disable')
                                                        </button>
                                                    @endif
                                                    <button class="dropdown-item assetFileBtn"
                                                        data-lesson_id="{{ $lesson->id }}"
                                                        data-asset_file="{{ asset(getFilePath('lesson_asset')) }}">
                                                        <i class="las la-book"></i> @lang('Asset File')
                                                    </button>
                                                    @if ($lesson->asset_path)
                                                        <a href="{{ route('admin.course.lesson.asset.download', $lesson->id) }}"
                                                            class="dropdown-item" data-lesson_id="{{ $lesson->id }}"
                                                            data-asset_file="{{ asset(getFilePath('lesson_asset')) }}">
                                                            <i class="las la-download"></i> @lang('Download Asset')
                                                        </a>
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
                @if ($lessons->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($lessons) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="lessonModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label>@lang('Image')</label>
                                    <x-image-uploader class="w-100" type="course" name="thumb_image" :required=false />
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="form-group">
                                    <label>@lang('Title')</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>

                                <input type="hidden" name="course_id" value="{{ $course->id }}">


                                <div class="form-group">
                                    <label>@lang('Section')</label>
                                    <select name="section_id" class="form-control select2"
                                        data-minimum-results-for-search="-1" required>
                                        <option hidden>@lang('Select One')</option>
                                        @foreach ($course->sections as $section)
                                            <option value="{{ $section->id }}" @selected(isset($sectionId) && $sectionId == $section->id)>
                                                {{ __($section->title) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($course->premium)
                                    <div class="form-group">
                                        <label>@lang('Premium')</label>
                                        <select name="premium" class="form-control select2"
                                            data-minimum-results-for-search="-1" required>
                                            <option value="1">@lang('Yes')</option>
                                            <option value="0">@lang('No')</option>
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label>@lang('Short Description')</label>
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

    <div id="lessonAssetModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST" id="assetForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Select Server')</label>
                            <select name="file_server" class="form-control">
                                <option value="0">@lang('Current Server')</option>
                                <option value="1">@lang('FTP Server')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Select Asset File') (<small>@lang('Supported files'): <b>@lang('zip')</b></small>)</label>
                            <input type="file" class="form-control" name="asset_file" accept=".zip" required>
                        </div>
                        <div class="form-group progressGroup">
                            <label>@lang('Upload Progress')</label>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated customWidth"
                                    role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
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
    <x-back route="{{ route('admin.course.section', $course->id) }}" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            let modal = $('#lessonModal');
            let action = `{{ route('admin.course.lesson.save') }}`;
            let defaultImage = `{{ getImage(getFilePath('course'), getFileSize('course')) }}`;
            let premiumCourse = `{{ $course->premium }}`;

            $('.addBtn').on('click', function() {
                modal.find('.modal-title').text(`@lang('Add New Lesson')`);
                modal.find('.image-upload-preview').css('background-image', `url(${defaultImage})`);
                modal.find('form').attr('action', action);
                modal.find('[name=thumb_image]').attr('required', true);
                modal.find('.imageLabel').addClass('required');
                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                modal.find('.modal-title').text(`@lang('Update Lesson')`);
                let data = $(this).data();
                let lesson = data.lesson;

                modal.find('[name=title]').val(lesson.title);
                modal.find('[name=section_id]').val(lesson.section_id);
                modal.find('[name=premium]').val(lesson.premium);
                modal.find('[name=description]').val(lesson.description);
                modal.find('.image-upload-preview').css('background-image', `url(${data.image})`);

                modal.find('[name=thumb_image]').removeAttr('required');
                modal.find('.imageLabel').removeClass('required');

                modal.find('form').attr('action', `${action}/${lesson.id}`);
                modal.modal('show');
            });

            modal.on('hidden.bs.modal', function() {
                modal.find('form')[0].reset();
            });


            let fileServer;
            let lessonId;

            $('.assetFileBtn').on('click', function() {
                let modal = $('#lessonAssetModal');
                lessonId = $(this).data('lesson_id');
                let assetFile = $(this).data('asset_file');
                modal.find('.modal-title').text(assetFile ? `@lang('Update Asset File')` : `@lang('Add New Asset File')`);
                modal.modal('show');
            });


            $('[name=file_server]').on('change', function() {
                fileServer = $(this).val();
                fileServer == 1 ? $('.progressGroup').hide() : $('.progressGroup').show();
            }).change();

            $('#assetForm').on('submit', function(e) {
                e.preventDefault();
                const file = e.target.asset_file.files[0];
                const url = `{{ route('admin.course.lesson.asset.upload', '') }}/${lessonId}`;

                if (fileServer == 0) {
                    const fileExtension = '.' + file.name.split('.').pop();
                    const chunkSize = 500 * 1024;
                    const totalChunks = Math.ceil(file.size / chunkSize);
                    uploadFileInChunks(file, chunkSize, totalChunks, fileExtension, fileServer, url);
                } else {
                    $(this).find('button[type="submit"]').text(`@lang('Uploading....')`).attr('disabled', true);
                    let formData = new FormData();

                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('asset_file', file);
                    formData.append('file_server', fileServer);

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status == 'error') {
                                notify('error', response.message);
                            }
                            notify('success', response.message);
                            setInterval(function() {
                                location.reload();
                            }, 1000);
                        }
                    });
                }
            });

        })(jQuery);
    </script>

    @include('admin.course.chunk_upload_js')
@endpush
