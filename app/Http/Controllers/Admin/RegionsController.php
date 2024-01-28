<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Faq;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = __("menus.regions");
        $regions = Region::all();
        $cities = City::all();
        return view('admin.regions.index',compact('title','regions','cities'));
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
        $region = new Region();
        $region->region_ar = $request->input('region_ar');
        $region->region = $request->input('region');
        $region->city_id = $request->input('city_id');

        if ($region->save()) {
            Session::flash('alert-success',__('message.new_region_added'));
            return redirect('admin/regions');
        } else {

            Session::flash('message',__('message.not_added'));
            return redirect('admin/regions');
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

        $title = __('label.edit_region');
        $region = Region::find($id);
        $cities = City::all();
        return view('admin.regions.edit',compact('region','title','cities'));
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
        $reg = Region::find($request->id);
        $reg->region_ar = $request->input('region_ar');
        $reg->region = $request->input('region');
        $reg->city_id = $request->input('city_id');

        if ($reg->update()) {
            Session::flash('alert-success',__('message.region_edited'));
            return redirect('admin/regions/');
        } else {
            Session::flash('alert-success',__('message.not_edited'));
            return redirect('admin/regions/');
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
        $res = Region::find($id)->delete();
//        if ($res){
//            Session::flash('alert-success','Offer Deleted !!');
//            return redirect('admin/offers');
//        }
        return back()->with('success','Faq deleted successfully');
    }
}
