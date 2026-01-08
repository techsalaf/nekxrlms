<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoursePurchase;
use App\Models\NotificationLog;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function purchaseHistory(Request $request)
    {
        $coursePurchases = CoursePurchase::searchable(['user:username', 'course:title'])->dateFilter()->with('user', 'course');

        if ($request->days !== null && is_numeric($request->days) && ((int) $request->days == $request->days)) {
            $coursePurchases->where('created_at', '>=', today()->subDay($request->days));
        }

        $purchasedAmount = $coursePurchases->sum('purchased_amount');
        $coursePurchases = $coursePurchases->orderBy('id', 'desc')->paginate(getPaginate());

        $pageTitle = 'Purchased History | ' . showAmount($purchasedAmount);

        return view('admin.reports.purchase_history', compact('pageTitle', 'coursePurchases', 'purchasedAmount'));
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';
        $loginLogs = UserLogin::orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip', $ip)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs = NotificationLog::orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }
}
