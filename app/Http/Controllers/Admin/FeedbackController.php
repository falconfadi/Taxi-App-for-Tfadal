<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cancel_reason;
use App\Http\Requests\StoreCancel_reasonRequest;
use App\Http\Requests\UpdateCancel_reasonRequest;
use App\Models\Feedbak_reason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        parent::__construct();

    }
    public function index()
    {
        $title = __('menus.feed_Reasons');
        //$reasons = Cancel_reason::all();
        $feedbacks = Feedbak_reason::all();
        return view('admin.feedback.index',compact('feedbacks','title'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCancel_reasonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cr = new Feedbak_reason();
        $cr->arabic_title = $request->input('arabic_title');
        $cr->english_title = $request->input('english_title');
        //$cr->for_users = $request->input('for_users');

        if ($cr->save()) {
            Session::flash('alert-success',__('message.feedback_added'));
            return redirect('admin/feedback_reasons');
        } else {
            Session::flash('alert-success',__('message.not_added'));
            return redirect('admin/feedback_reasons');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cancel_reason  $cancel_reason
     * @return \Illuminate\Http\Response
     */
    public function show(Cancel_reason $cancel_reason)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cancel_reason  $cancel_reason
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = __('label.edit_feedback_reason');
        $feed = Feedbak_reason::find($id);
        if($feed)
            return view('admin.feedback.edit',compact('feed','title'));
        else
            abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCancel_reasonRequest  $request
     * @param  \App\Models\Cancel_reason  $cancel_reason
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $reason = Feedbak_reason::find($request->id);

        $reason->arabic_title = $request->input('arabic_title');
        $reason->english_title = $request->input('english_title');

        if ($reason->update()) {

            Session::flash('alert-success',__('message.feedback_edited'));
            return redirect('admin/feedback_reasons');
        } else {
            Session::flash('alert-success',__('message.feedback_not_edited'));
            return redirect('admin/feedback_reasons');
        }
    }

    public function destroy($id)
    {
        $res = Cancel_reason::find($id)->delete();
//        if ($res){
//            Session::flash('alert-success','Offer Deleted !!');
//            return redirect('admin/offers');
//        }
        return back()->with('success','Reason deleted successfully');
    }
}
