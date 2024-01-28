<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = __("menus.Faqs");
        $faqs = Faq::all();
        return view('admin.faqs.index',compact('title','faqs'));
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
        $faq = new Faq();
        $faq->arabic_question = $request->input('arabic_question');
        $faq->arabic_answer = $request->input('arabic_answer');
        $faq->english_question = $request->input('english_question');
        $faq->english_answer = $request->input('english_answer');

        if ($faq->save()) {
            Session::flash('alert-success',__('message.new_faqs_added'));
            return redirect('admin/faqs');
        } else {

            Session::flash('message',__('message.not_added'));
            return redirect('admin/faqs');
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

        $title = __('label.edit_faq');
        $faq = Faq::find($id);
        //var_dump($cars);
        return view('admin.faqs.edit',compact('faq','title'));
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
        $faq = Faq::find($request->id);

        $faq->arabic_question = $request->input('arabic_question');
        $faq->arabic_answer = $request->input('arabic_answer');
        $faq->english_question = $request->input('english_question');
        $faq->english_answer = $request->input('english_answer');

        if ($faq->update()) {
            //$request->session()->flash('alert-success', __('Setting has been Edited'));
            Session::flash('alert-success',__('message.faq_edited'));
            return redirect('admin/faqs/');
        } else {
            Session::flash('alert-success',__('message.not_edited'));
            return redirect('admin/faqs/');
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
        $res = Faq::find($id)->delete();
//        if ($res){
//            Session::flash('alert-success','Offer Deleted !!');
//            return redirect('admin/offers');
//        }
        return back()->with('success','Faq deleted successfully');
    }
}
