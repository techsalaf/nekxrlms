<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    function list() {
        $pageTitle = 'Coupon List';
        $coupons   = Coupon::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.coupon.list', compact('pageTitle', 'coupons'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'name'          => 'required',
            'code'          => 'required',
            'discount_type' => 'required|in:0,1',
            'amount'        => 'required|numeric|gt:0',
            'minimum_spend' => 'required|numeric|gt:0',
            'maximum_spend' => 'required|numeric|gt:minimum_spend',
        ]);

        if($id){
            $coupon = Coupon::find($id);
            $notification = 'updated';
        }else{
            $coupon = new Coupon();
            $notification = 'added';
        }

        $coupon->name = $request->name;
        $coupon->code = $request->code;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_amount = $request->amount;
        $coupon->minimum_spend = $request->minimum_spend;
        $coupon->maximum_spend = $request->maximum_spend;
        $coupon->description = $request->description;
        $coupon->save();


        $notify[]=['success',"Coupon $notification successfully"];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Coupon::changeStatus($id);
    }
    
}
