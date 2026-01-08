@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Upload video for course list page')</h5>
                </div>
                <div class="card-body">
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <video controls preload="auto" width="100%">
                                        <source src="{{ asset(getFilePath('coursePageVideo').'/'.gs('course_video_one')) }}">
                                    </video>
                                    <div class="form-group videoFileGroup">
                                        <label>@lang('Video') <small class="mt-2">@lang('Supported files'): <b>@lang('mp4')</b></small></label>
                                        <input type="file" class="form-control" name="video_one" accept=".mp4">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <video controls preload="auto" width="100%">
                                        <source src="{{ asset(getFilePath('coursePageVideo').'/'.gs('course_video_two')) }}">
                                    </video>
                                    <div class="form-group videoFileGroup">
                                        <label>@lang('Video') <small class="mt-2">@lang('Supported files'): <b>@lang('mp4')</b></small></label>
                                        <input type="file" class="form-control" name="video_two" accept=".mp4">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn--primary w-100">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
