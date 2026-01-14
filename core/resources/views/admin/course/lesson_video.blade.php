@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Course Information')</h5>
                </div>
                <div class="card-body">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                @if ($lesson->server == 2)
                                    <iframe width="100%" height="400px" src="{{ convertToEmbedUrl($lesson->path) }}"
                                        frameborder="0" allowfullscreen></iframe>
                                @else
                                    <video controls preload="auto" width="100%"
                                        poster="{{ getImage(getFilePath('video_thumb') . '/' . $lesson->thumb_image, getFileSize('video_thumb')) }}"
                                        id="myVideo">
                                        <source src="{{ getVideoPath($lesson) }}">
                                    </video>
                                @endif
                            </div>

                            <div class="col-lg-6">
                                <form enctype="multipart/form-data" id="videoForm">
                                    <div class="form-group">
                                        <label>@lang('Select Server')</label>
                                        <select name="file_server" class="form-control" required>
                                            <option value="0" @selected($lesson->server == 0)>@lang('Current Server')</option>
                                            <option value="1" @selected($lesson->server == 1)>@lang('FTP Server')</option>
                                            <option value="2" @selected($lesson->server == 2)>@lang('Youtube Link')</option>
                                            <option value="3" @selected($lesson->server == 3)>@lang('Loom Link')</option>
                                        </select>
                                    </div>

                                    <div class="form-group videoFileGroup">
                                        <label>@lang('Video') <small class="mt-2">@lang('Supported files'):
                                                <b>@lang('mp4')</b></small></label>
                                        <input type="file" class="form-control" name="video" accept=".mp4">
                                    </div>
                                    <div class="form-group progressGroup">
                                        <label>@lang('Upload Progress')</label>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated customWidth"
                                                role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group youtubeGroup">
                                        <label>@lang('Youtube Video Link')</label>
                                        <input type="text" name="youtube_link"
                                            @if ($lesson->server == 2) value="{{ $lesson->path }}" @endif
                                            class="form-control">
                                    </div>
                                    <div class="form-group youtubeGroup">
                                        <label>@lang('Duration') @lang('HH:MM:SS')</label>
                                        <input type="text" name="duration"
                                            value="{{ now()->startOfDay()->addSeconds($lesson->video_duration)->format('H:i:s') }}"
                                            placeholder="@lang('02:17:45')" class="form-control">
                                    </div>
                                    <div class="form-group loomGroup" style="display:none;">
                                        <label>@lang('Loom Video Link')</label>
                                        <input type="text" name="loom_link"
                                            @if ($lesson->server == 3) value="{{ $lesson->path }}" @endif
                                            class="form-control" placeholder="@lang('e.g., https://www.loom.com/share/...')">
                                    </div>
                                    <div class="form-group loomGroup" style="display:none;">
                                        <label>@lang('Duration') @lang('HH:MM:SS')</label>
                                        <input type="text" name="duration"
                                            value="{{ now()->startOfDay()->addSeconds($lesson->video_duration)->format('H:i:s') }}"
                                            placeholder="@lang('02:17:45')" class="form-control">
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" id="uploadBtn"
                                            class="btn btn--primary w-100 h-45">@lang('Upload')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";

            var videoServer;

            $('[name=file_server]').on('change', function() {
                videoServer = $('[name=file_server]').val();

                if (videoServer == 0) {
                    $('.videoFileGroup').show();
                    $('.progressGroup').show();
                    $('.youtubeGroup').hide();
                    $('.loomGroup').hide();
                } else if (videoServer == 1) {
                    $('.videoFileGroup').show();
                    $('.progressGroup').hide();
                    $('.youtubeGroup').hide();
                    $('.loomGroup').hide();
                } else if (videoServer == 2) {
                    $('.videoFileGroup').hide();
                    $('.progressGroup').hide();
                    $('.youtubeGroup').show();
                    $('.loomGroup').hide();
                } else if (videoServer == 3) {
                    $('.videoFileGroup').hide();
                    $('.progressGroup').hide();
                    $('.youtubeGroup').hide();
                    $('.loomGroup').show();
                }

            }).change();

            $('#videoForm').on('submit', function(e) {
                e.preventDefault();

                let file;
                let fileInput = e.target.video;
                let videoDuration;

                if (fileInput.files.length > 0) {
                    file = fileInput.files[0];
                }

                if (!file && videoServer != 2 && videoServer != 3) {
                    notify('error', 'Please select a file');
                    return false;
                }

                if (videoServer != 2 && videoServer != 3) {
                    let video = document.createElement('video');
                    video.preload = 'metadata';
                    video.src = URL.createObjectURL(file);

                    video.onloadedmetadata = function() {
                        videoDuration = video.duration;
                        uploadVideo(file, videoDuration);
                    };
                } else {
                    // For Youtube (2) and Loom (3), read duration from the visible group's input
                    if (videoServer == 2) {
                        videoDuration = $('.youtubeGroup input[name="duration"]').val();
                    } else if (videoServer == 3) {
                        videoDuration = $('.loomGroup input[name="duration"]').val();
                    } else {
                        videoDuration = $('[name=duration]').val();
                    }
                    let parts = videoDuration.split(':');

                    if (parts.length !== 3) {
                        notify('error', `@lang('Invalid time format.')`);
                        return false;
                    }

                    let hours = parseInt(parts[0], 10);
                    let minutes = parseInt(parts[1], 10);
                    let seconds = parseInt(parts[2], 10);

                    if (!isNaN(hours) && !isNaN(minutes) && !isNaN(seconds) &&
                        hours >= 0 && hours <= 23 && minutes >= 0 && minutes <= 59 && seconds >= 0 && seconds <=
                        59) {

                        videoDuration = hours * 3600 + minutes * 60 + seconds;
                        uploadVideo(file, videoDuration);
                    } else {
                        notify('error', `@lang('Invalid time format.')`);
                        return false;
                    }
                }

            });

            function uploadVideo(file, videoDuration = 0) {
                const url = `{{ route('admin.course.lesson.video.upload', $lesson->id) }}`;

                if (videoServer == 0) {
                    const fileExtension = '.' + file.name.split('.').pop();
                    const chunkSize = 500 * 1024;
                    const totalChunks = Math.ceil(file.size / chunkSize);
                    uploadFileInChunks(file, chunkSize, totalChunks, fileExtension, videoServer, url, videoDuration);
                } else {
                    if (videoServer == 1) {
                        $('#uploadBtn').text(`@lang('Uploading.....')`).attr('disabled', true);
                    }
                    let youtubeLink = videoServer == 2 ? $('[name=youtube_link]').val() : '';
                    let loomLink = videoServer == 3 ? $('[name=loom_link]').val() : '';
                    let formData = new FormData();

                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('file', file);
                    formData.append('youtube_link', youtubeLink);
                    formData.append('loom_link', loomLink);
                    formData.append('file_server', videoServer);
                    formData.append('video_duration', videoDuration);

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
            }

        })(jQuery);
    </script>

    @include('admin.course.chunk_upload_js')
@endpush

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.course.section.lessons', $lesson->section->id) }}" />
@endpush

