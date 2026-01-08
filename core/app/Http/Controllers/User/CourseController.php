<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\Review;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function list()
    {
        $pageTitle = 'My Course';
        $myCourses = CoursePurchase::with(['course' => function ($course) {
            $course->withCount(['lessons' => function ($lesson) {
                $lesson->active();
            }])->withSum(['lessons as total_duration' => function ($lesson) {
                $lesson->active();
            }], 'video_duration');
        }])->where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate());

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
            $coursePurchase = CoursePurchase::where('user_id', auth()->id())->where('course_id', $request->course_id)->exists();
            if (!$coursePurchase) {
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
