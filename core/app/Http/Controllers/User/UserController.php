<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseAccessControl;
use App\Models\CoursePurchase;
use App\Models\DeviceToken;
use App\Models\Review;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
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

        $myCourses = $courseItems->take(10);

        $widget['total_purchased']      = $purchasedCourseIds->count();
        $widget['total_accessible']     = $courseItems->count();
        $widget['accessible_purchased'] = $courseItems->where('is_purchased', true)->count();
        $widget['total_manual_access']  = $courseItems->where('is_manual_access', true)->count();
        $widget['total_review']         = Review::where('user_id', auth()->id())->count();
        $widget['total_support_ticket'] = SupportTicket::where('user_id', auth()->id())->count();

        $myReviews = Review::where('user_id', auth()->id())->pluck('course_id')->toArray();
        return view('Template::user.dashboard', compact('pageTitle', 'myCourses', 'widget', 'myReviews'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Payments History';
        $deposits = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }

    public function userDataSubmit(Request $request)
    {

        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ]);


        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
        $user->username     = $request->username;


        $user->address          = $request->address;
        $user->city             = $request->city;
        $user->state            = $request->state;
        $user->zip              = $request->zip;
        $user->country_name     = @$request->country;
        $user->dial_code        = $request->mobile_code;
        $user->profile_complete = Status::YES;
        $user->save();

        return to_route('user.home');
    }


    public function addDeviceToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function reviews()
    {
        $pageTitle = 'My Reviews';
        $myReviews = Review::with('course')->where('user_id', auth()->id())->paginate(getPaginate());
        return view(activeTemplate() . 'user.reviews', compact('pageTitle', 'myReviews'));
    }
}
