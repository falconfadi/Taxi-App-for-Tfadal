<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = __("menus.cities");
        $cities = City::all();
        return view('admin.cities.index',compact('title','cities'));
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
        $city = new City();
        $city->name_ar = $request->input('name_ar');
        $city->name = $request->input('name');

        if ($city->save()) {
            Session::flash('alert-success',__('message.new_city_added'));
            return redirect('admin/cities');
        } else {
            Session::flash('message',__('message.not_added'));
            return redirect('admin/cities');
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

        $title = __('label.edit_city');
        $city = City::find($id);

        return view('admin.cities.edit',compact('city','title'));
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
        $city = City::find($request->id);
        $city->name_ar = $request->input('name_ar');
        $city->name = $request->input('name');

        if ($city->update()) {
            Session::flash('alert-success',__('message.city_edited'));
            return redirect('admin/cities/');
        } else {
            Session::flash('alert-success',__('message.not_edited'));
            return redirect('admin/cities/');
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
        $res = City::find($id)->delete();
//        if ($res){
//            Session::flash('alert-success','Offer Deleted !!');
//            return redirect('admin/offers');
//        }
        return back()->with('success','Faq deleted successfully');
    }
}
