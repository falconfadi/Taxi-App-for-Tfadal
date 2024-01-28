<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cancel_reason;
use App\Http\Requests\StoreCancel_reasonRequest;
use App\Http\Requests\UpdateCancel_reasonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class CancelReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');

        App::setLocale('ar');
        session()->put('locale', 'ar');
    }
    public function index()
    {
        $title = __('menus.Cancel_Reasons');
        $reasons = Cancel_reason::all();
        return view('admin.cancels.index',compact('reasons','title'));

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
        $cr = new Cancel_reason();
        $cr->arabic_title = $request->input('arabic_title');
        $cr->english_title = $request->input('english_title');
        $cr->for_users = $request->input('for_users');

        if ($cr->save()) {
            Session::flash('alert-success','New reason has been Added');
            return redirect('admin/cancel_reasons');
        } else {
            Session::flash('alert-success','Reason Not Added !!');
            return redirect('admin/cancel_reasons');
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
        $title = __('label.edit_reason');
        $reason = Cancel_reason::find($id);
        if($reason)
            return view('admin.cancels.edit',compact('reason','title'));
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
        $reason = Cancel_reason::find($request->id);
        //var_dump($request->all());exit();

        $reason->arabic_title = $request->input('arabic_title');
        $reason->english_title = $request->input('english_title');
        $reason->for_users = $request->input('for_users');


        if ($reason->update()) {
            //$request->session()->flash('alert-success', __('Setting has been Edited'));
            Session::flash('alert-success','Offer has been Edited');
            return redirect('admin/cancel_reasons');
        } else {
            Session::flash('alert-success','Offer Not Edited !!');
            return redirect('admin/cancel_reasons');
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
