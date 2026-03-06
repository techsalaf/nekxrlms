<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CourseAccessControl;
use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CourseController extends Controller
{
    public function list()
    {
        $pageTitle = 'My Course';
        $userId = auth()->id();

        $purchases = CoursePurchase::where('user_id', $userId)->orderBy('id', 'desc')->get()->keyBy('course_id');
        $purchasedCourseIds = $purchases->keys();

        $accessOverrides = collect();
        $manuallyUnlockedCourseIds = collect();
        $manuallyLockedCourseIds = collect();

        if (Schema::hasTable('course_access_controls')) {
            $accessOverrides = CourseAccessControl::where('user_id', $userId)->get()->keyBy('course_id');
            $manuallyUnlockedCourseIds = $accessOverrides->where('is_locked', false)->keys();
            $manuallyLockedCourseIds = $accessOverrides->where('is_locked', true)->keys();
        }

        $effectiveCourseIds = $purchasedCourseIds
            ->merge($manuallyUnlockedCourseIds)
            ->unique()
            ->diff($manuallyLockedCourseIds)
            ->values();

        $courses = Course::active()->whereIn('id', $effectiveCourseIds)
            ->withCount(['lessons' => function ($lesson) {
                $lesson->active();
            }])
            ->withSum(['lessons as total_duration' => function ($lesson) {
                $lesson->active();
            }], 'video_duration')
            ->with(['lessons' => function ($lesson) {
                $lesson->active()->orderBy('section_id')->orderBy('id');
            }])
            ->get()
            ->keyBy('id');

        $courseItems = $effectiveCourseIds->map(function ($courseId) use ($courses, $purchases, $accessOverrides) {
            $course = $courses->get($courseId);

            if (!$course) {
                return null;
            }

            $purchase = $purchases->get($courseId);
            $override = $accessOverrides->get($courseId);
            $isPurchased = !is_null($purchase);

            return (object) [
                'course' => $course,
                'purchased_amount' => $purchase->purchased_amount ?? 0,
                'created_at' => $purchase->created_at ?? optional($override)->created_at ?? now(),
                'is_manual_access' => !$isPurchased,
                'is_purchased' => $isPurchased,
            ];
        })->filter()->sortByDesc(function ($item) {
            return $item->created_at;
        })->values();

        $perPage = getPaginate();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $myCourses = new LengthAwarePaginator(
            $courseItems->forPage($currentPage, $perPage)->values(),
            $courseItems->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        $myReviews = Review::where('user_id', auth()->id())->pluck('course_id')->toArray();

        return view(activeTemplate() . 'user.course.list', compact('pageTitle', 'myCourses', 'myReviews'));
    }

    public function review(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'rating'    => 'required|between:1,5',
            'review'    => 'required',
        ]);

        $course = Course::active()->find($request->course_id);

        if (!$course) {
            $notify[] = ['error', 'Course not found!'];
            return back()->withNotify($notify);
        }

        if ($course->premium) {
            if (!coursePermissionById($course->id, (bool) $course->premium, auth()->id())) {
                $notify[] = ['error', 'You can\'t review the premium course until purchased'];
                return back()->withNotify($notify);
            }
        }


        $review = Review::where('user_id', auth()->id())->where('course_id', $request->course_id)->first();

        if ($review) {
            $course->total_rating = $course->total_rating - $review->rating + $request->rating;
            $message              = 'Review updated successfully';
        } else {
            $review            = new Review();
            $review->user_id   = auth()->id();
            $review->course_id = $request->course_id;

            $course->total_rating += $request->rating;
            $course->total_review += 1;
            $message = 'Review added successfully';
        }

        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        $course->avg_rating = $course->total_rating / $course->total_review;
        $course->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }
}
