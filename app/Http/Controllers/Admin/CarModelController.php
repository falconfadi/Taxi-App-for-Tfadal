<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car_model;
use App\Models\Make;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CarModelController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:admin');
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title =  __('menus.Car_Models');
        $models = Car_model::all();
        $brands = Brand::all();

        return view('admin.car-models.index',compact('models','brands','title'));
    }
    public function create()
    {
        $title =  __('page.new_Car_Model');
        $makes = Make::all();
        return view('admin.model.create',compact('makes','title'));
    }

    public function store(Request $request)
    {
        //var_dump($request->all());exit();
        $model = new Car_model();
        $model->model = $request->input('model');
        $model->brand_id = $request->input('brand_id');

        if ($model->save()) {
            //activity()->log('Look mum, I logged something');
            Session::flash('alert-success','New Model has been Added');
            return redirect('admin/car-models');
        } else {

            Session::flash('alert-success','Model Not Added !!');
            return redirect('admin/car-models');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car_model  $car_model
     * @return \Illuminate\Http\Response
     */
    public function show(Car_model $car_model)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car_model  $car_model
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = __('page.Edit_Car_Model');

        $model = Car_model::find($id);
        $brands = Brand::all();
        return view('admin.car-models.edit',compact('brands','model','title'));
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
        $car_model = Car_model::find($request->id);
        //var_dump($request->all());exit();
        $car_model->model = $request->input('model');
        $car_model->brand_id = $request->input('brand_id');

        if ($car_model->update()) {
            //$request->session()->flash('alert-success', __('Setting has been Edited'));
            Session::flash('alert-success','Model has been Edited');
            return redirect('admin/car-models/');
        } else {
            Session::flash('alert-success','Model Not Edited !!');
            return redirect('admin/car-models/');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car_model  $car_model
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = Car_model::find($id)->delete();
        //return back()->with('success','User deleted successfully');
        return redirect('admin/car-models');
    }
    //ajax
    public function getByBrandId($id = 0)
    {
        $data = Car_model::where('brand_id', '=', $id)->get();
        return response()->json($data);
    }
}
