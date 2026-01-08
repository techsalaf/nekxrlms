@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Course Information')</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.course.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>@lang('Image')</label>
                                        <x-image-uploader class="w-100" type="course" :required=true />
                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>@lang('Title')</label>
                                        <input type="text" class="form-control" name="title"
                                            value="{{ old('title') }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Category')</label>
                                                <select name="category_id" class="form-control select2" required>
                                                    <option hidden>@lang('Select One')</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                                            {{ __($category->name) }} @if (!$category->status)
                                                                (@lang('Inactive'))
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Premium')</label>
                                                <select name="premium" class="form-control select2"
                                                    data-minimum-results-for-search="-1">
                                                    <option value="1">@lang('Yes')</option>
                                                    <option @selected(request()->has('free')) value="0">@lang('No')
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 priceArea">
                                            <div class="form-group">
                                                <label>@lang('Price') <span class="text--danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" step="any" min="0" class="form-control"
                                                        name="price" value="{{ old('price') }}">
                                                    <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 priceArea">
                                            <div class="form-group">
                                                <label>@lang('Price After Discount')</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" min="0" class="form-control"
                                                        name="discount_price" value="{{ old('discount_price') }}">
                                                    <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group select2-parent position-relative">
                                        <label>@lang('Meta Keywords')</label>
                                        <small class="ms-2 mt-2  ">@lang('Separate multiple keywords by') <code>,</code>(@lang('comma'))
                                            @lang('or') <code>@lang('enter')</code> @lang('key')</small>
                                        <select name="meta_keyword[]" class="form-control select2-auto-tokenize"
                                            multiple="multiple">
                                            @foreach (old('meta_keyword') ?? [] as $option)
                                                <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('Short Description')</label>
                                        <textarea class="form-control" name="short_description" rows="5" required>{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-12">
                                        <label>@lang('Description')</label>
                                        <textarea name="description" rows="10" class="form-control nicEdit"></textarea>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="border rounded p-2">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                <p>@lang('What you will learn?')</p>
                                                <button type="button" class="btn btn--primary addLearnBtn"><i
                                                        class="las la-plus-circle"></i>@lang('Add New')</button>
                                            </div>
                                            <div class="addedLearn mt--3">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="border rounded p-2">

                                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                <p>@lang('What course includes?')</p>
                                                <button type="button" class="btn btn--primary addIncludeBtn"><i
                                                        class="las la-plus-circle"></i>@lang('Add New')</button>
                                            </div>
                                            <div class="addedInclude mt--3">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45 mt-4">@lang('Submit')</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('style-lib')
    <link href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/fontawesome-iconpicker.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('[name=premium]').on('change', function() {
                if ($(this).val() * 1) {
                    $('.priceArea').show();
                    $('[name=price]').attr('required', 'required');
                } else {
                    $('.priceArea').hide();
                    $('[name=price]').removeAttr('required');
                }
            }).change();

            $('.addLearnBtn').on('click', function() {
                let html = `<div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="learns[]" placeholder="@lang('i.e: You will learn how to ...')" required>
                                    <button class="btn btn--danger input-group-text deleteLearn" type="button"><i class="las la-times m-0"></i></button>
                                </div>
                            </div>`;

                $('.addedLearn').append(html);
            });

            $(document).on('click', '.deleteLearn', function() {
                $(this).closest('.form-group').remove();
            });

            $('.addIncludeBtn').on('click', function() {
                let html = `  <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control iconPicker icon" autocomplete="off" placeholder="@lang('icon')" name="includes[icon][]" value="" required>
                                            <span class="input-group-text  input-group-addon" data-icon="las la-home" role="iconpicker"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="includes[text][]" placeholder="@lang('i.e: Community support')"  value="">
                                            <button class="btn btn--danger input-group-text deleteInclude" type="button"><i class="las la-times m-0"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                $('.addedInclude').append(html);
            });

            $(document).on('click', '.deleteInclude', function() {
                $(this).closest('.row').remove();
            });

            $(document).on('focus', '[name="includes[icon][]"]', function() {
                $('.iconPicker').iconpicker().on('iconpickerSelected', function(e) {
                    $(this).closest('.form-group').find('.iconpicker-input').val(
                        `<i class="${e.iconpickerValue}"></i>`);
                });
            });

        })(jQuery);
    </script>
@endpush
@push('style')
    <style>
        .mt--3:has(.form-group) {
            margin-top: 1rem !important;
        }
    </style>
@endpush
