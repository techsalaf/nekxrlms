@php
    $category = getContent('category.content', true);
    $featuredCategories = App\Models\Category::withCount('courses', 'lessons')
        ->featured()
        ->get();
@endphp

<section class="courses py-80">
    <div class="custom--container">
        <h3 class="courses-title">{{ __(@$category->data_values->title) }}</h3>
        <p class="courses-description">{{ __(@$category->data_values->subtitle) }}</p>
        <div class="row g-3 gx-sm-2 gy-sm-3 gx-md-3 gy-sm-4 gx-xl-4 gy-xl-5">
            @foreach ($featuredCategories as $fCategory)
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <a href="{{ route('category.course', [slug($fCategory->name), $fCategory->id]) }}" class="courses-card courses-card--{{ ($loop->index % 8) + 1 }}">
                        <div class="courses-card-header">
                            <div class="courses-card-icon">
                                <img src="{{ getImage(getFilePath('category') . '/' . $fCategory->image, getFileSize('category')) }}" alt="Closing Tag Rounded">
                            </div>
                        </div>

                        <div class="courses-card-body">
                            <h5 class="courses-card-title">{{ __($fCategory->name) }}</h5>

                            <ul class="courses-card-meta">
                                <li class="courses-card-meta-item">
                                    <span class="text">{{ $fCategory->courses_count }} @lang('Courses')</span>
                                </li>
                                <li class="courses-card-meta-item">
                                    <span class="text">{{ $fCategory->lessons_count }} @lang('Lessons')</span>
                                </li>
                            </ul>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
