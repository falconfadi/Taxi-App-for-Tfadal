<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\FarwayRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FarwayRegionController extends Controller
{
    public function index()
    {
        $title = __("menus.farway_regions");
        $farway_regions = FarwayRegion::all();
        return view('admin.farway_regions.index',compact('title','farway_regions'));
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
        $faq = new FarwayRegion();
        $faq->from = $request->input('from');
        $faq->to = $request->input('to');
        $faq->price = $request->input('price');
        if ($faq->save()) {
            Session::flash('alert-success',__('message.new_item_added'));
            return redirect('admin/farway_regions/');
        } else {

            Session::flash('message',__('message.not_added'));
            return redirect('admin/farway_regions/');
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

        $title = __('label.edit_item');
        $fa = FarwayRegion::find($id);
        //var_dump($cars);
        return view('admin.farway_regions.edit',compact('fa','title'));
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
        //var_dump($request->id);exit();
        $faq = FarwayRegion::find($request->id);
        //var_dump($faq);exit();
        $faq->from = $request->input('from');
        $faq->to = $request->input('to');
        $faq->price = $request->input('price');

        if ($faq->update()) {
            Session::flash('alert-success',__('message.item_edited'));
            return redirect('admin/farway_regions/');
        } else {
            Session::flash('alert-success',__('message.not_edited'));
            return redirect('admin/farway_regions/');
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
        $res = FarwayRegion::find($id)->delete();
        return back()->with('success',' deleted successfully');
    }
}
