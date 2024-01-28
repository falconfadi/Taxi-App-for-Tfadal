<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index()
    {
        $title = __('menus.Slider');
        $sliders = Slider::all();

        return view('admin.slider.index',compact('sliders','title'));
    }

    public function store(Request $request)
    {
        $messages = [
            'image.mimes' => trans('validation.mimes'),
            'image.max.file' => trans('validation.max.file'),
            'title_ar.required' => trans('validation.required')
        ];
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title_ar' =>'required'
        ],$messages);
        $slider = new Slider();
        if ($validator->fails()) {
            return redirect('admin/slider/')
                ->withErrors($validator)
                ->withInput();
        }

        $slider->image = '';
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = 'sliders/' . md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            if ($file->move('storage/sliders/', $file_name)) {
                $slider->image = $file_name;
            }
        }
        $slider->title_en = $request->input('title_en');
        $slider->title_ar = $request->input('title_ar');

        if ( $slider->save()){
            $request->session()->flash('alert-success', __('message.slider_added'));
            return redirect('admin/slider');
        }else{
            $request->session()->flash('alert-danger', 'Slider has NOT been Added!');
            return redirect('admin/slider');
        }

    }

    public function edit($id)
    {
        $title = __('menus.Edit_slider');
        $slider = Slider::findOrFail($id);

        return view('admin.slider.edit',compact('slider','title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car_model  $car_model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $messages = [
            'image.mimes' => trans('validation.mimes'),
            'image.max.file' => trans('validation.max.file'),
        ];
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ],$messages);
        $slider = Slider::find($request->slider_id);
        if ($validator->fails()) {
            return redirect('admin/slider/edit/'.$request->user_id)
                ->withErrors($validator)
                ->withInput();
        }

        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $file_name = 'sliders/'.md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            if ($file->move('storage/sliders/', $file_name)) {
                $slider->image = $file_name;
            }

            $slider->title_en = $request->input('title_en');
            $slider->title_ar = $request->input('title_ar');

            if ( $slider->update()){
                $request->session()->flash('alert-success', 'Slider has been updated!');
                return redirect('admin/slider');
            }else{
                $request->session()->flash('alert-danger', 'Slider has NOT been updated!');
                return redirect('admin/slider');
            }
        }
        else
        {
            $slider->title_en = $request->input('title_en');
            $slider->title_ar = $request->input('title_ar');
            //$ad->update();
            if ( $slider->update()){
                $request->session()->flash('alert-success', 'Slider has been updated!');
                return redirect('admin/slider');
            }else{
                $request->session()->flash('alert-danger', 'Slider has NOT been updated!');
                return redirect('admin/slider');
            }
        }
    }

    public function destroy($id)
    {
        $res = Slider::find($id)->delete();
//        if ($res){
//            Session::flash('alert-success','Offer Deleted !!');
//            return redirect('admin/offers');
//        }
        return back()->with('success','Faq deleted successfully');
    }
}
