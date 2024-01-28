<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AlertController extends Controller
{
    public function index()
    {
        $title = __("menus.Faqs");
        $alerts = Alert::all();
        return view('admin.alerts.index',compact('title','alerts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFaqRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $faq = new Alert();
        $faq->text = $request->input('text');
        $faq->text_en = $request->input('text_en');


        if ($faq->save()) {
            Session::flash('alert-success',__('message.new_alert_added'));
            return redirect('admin/alerts');
        } else {

            Session::flash('message',__('message.not_added'));
            return redirect('admin/alerts');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $title = __('label.edit_alert');
        $al = Alert::find($id);

        //var_dump($cars);
        return view('admin.alerts.edit',compact('al','title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateoffersRequest  $request
     * @param  \App\Models\offers  $offers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $faq = Alert::find($request->id);

        $faq->text = $request->input('text');
        $faq->text_en = $request->input('text_en');

        if ($faq->update()) {
            //$request->session()->flash('alert-success', __('Setting has been Edited'));
            Session::flash('alert-success',__('message.alert_edited'));
            return redirect('admin/alerts/');
        } else {
            Session::flash('alert-danger',__('message.not_edited'));
            return redirect('admin/alerts/edit/'.$faq->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = Alert::find($id)->delete();
//        if ($res){
//            Session::flash('alert-success','Offer Deleted !!');
//            return redirect('admin/offers');
//        }
        return back()->with('success','Faq deleted successfully');
    }
}
