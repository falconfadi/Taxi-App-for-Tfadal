<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car_model;
use App\Models\Car_type;
use App\Models\Color;
use App\Models\Driver;
use App\Models\Make;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    public function __construct()
    {
        parent::__construct();

//        $this->middleware('auth:admin');
//        App::setLocale('ar');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title =  __('menus.Cars');
        $cars = Car::all();
        $driversIds = array();
        foreach ($cars as $car){
            if($car && Driver::where('user_id',$car->driver_id)->first())
            $driversIds[$car->id] = Driver::where('user_id',$car->driver_id)->first()->id;
        }
        return view('admin.cars.index',compact('cars','title','driversIds'));
    }
    public function create()
    {
        $title = 'Car Model';
        $makes = Make::all();
        return view('admin.cars.create',compact('makes','title'));
    }

    public function edit($id)
    {
        $title = __('page.Edit_Car');
        $car = Car::findOrFail($id);
        //$user = User::find($car->driver_id);
        $driver = Driver::where('user_id',$car->driver_id)->with('driver_as_user')->first();
        //$car = Car::where('driver_id',$driver->user_id)->first();
        $car_model = Car_model::find($car->car_model);
        $car_types = Car_type::all();
        $brands = Brand::all();
        $colors = Color::all();
        return view('admin.cars.edit',compact('car','brands','driver','title','car_model','car_types','colors'));
    }
    public function carModel()
    {
        $title = 'Car Model';
        $brands = Brand::all();
        return view('admin.cars.create_car_model',compact('brands','title'));
    }
    public function carTypes()
    {
        $title =  __('menus.Car_types');
        $car_types = Car_type::all();
        return view('admin.cars.car_types',compact('car_types','title'));
    }

    public function editCarTypes($id)
    {
        $title = __('menus.Edit_Car_Type');
        $carType = Car_type::find($id);

        $car_types = Car_type::all();
        return view('admin.cars.edit_car_type',compact('carType','car_types','title'));
    }

    public function updateCarTypes(Request $request)
    {
        $type = Car_type::find($request->id);
        $messages = [
            'image.mimes' => trans('validation.mimes'),
            'image.max.file' => trans('validation.max.file'),
        ];
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ],$messages);

        if ($validator->fails()) {
            return redirect('admin/car_types/edit/'.$type->user_id)
                ->withErrors($validator)
                ->withInput();
        }

        if($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = 'carTypes/' . md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            if ($file->move('storage/carTypes/', $file_name)) {
                $type->image = $file_name;
            }
        }

        $type->name = $request->input('name');
        $type->other_type = $request->input('other_type');
        $type->name_ar = $request->input('name_ar');
        $type->price = $request->input('price');
        $type->minute_price = $request->input('minute_price');

        if ($type->update()) {
            Session::flash('alert-success','Car Type has been Updated');
            return redirect('admin/car-types');
        } else {
            Session::flash('alert-success','Car Type Not Updated !!');
            return redirect('admin/car-types');
        }
    }
    public function storeCarTypes(Request $request)
    {
        $type = new Car_type();

        $messages = [
            'image.mimes' => trans('validation.mimes'),
            'image.max.file' => trans('validation.max.file'),
        ];
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ],$messages);
        //$slider = new Slider();
        if ($validator->fails()) {
            return redirect('admin/car-types')
                ->withErrors($validator)
                ->withInput();
        }

        $type->image = '';
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = 'carTypes/' . md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            if ($file->move('storage/carTypes/', $file_name)) {
                $type->image = $file_name;
            }
        }
        $type->name = $request->input('name');
        $type->name_ar = $request->input('name_ar');
        $type->other_type = $request->input('other_type');
        $type->price = $request->input('price');
        $type->minute_price = $request->input('minute_price');

        if ($type->save()) {
            Session::flash('alert-success',__('message.new_car_added'));
            return redirect('admin/car-types');
        } else {

            Session::flash('alert-success',__('message.not_added'));
            return redirect('admin/car-types');
        }
    }
    public function store(Request $request)
    {
        $model = new Car_model();

        $model->model = $request->input('model');
        $model->make = $request->input('make');

        if ($model->save()) {
            Session::flash('message','New Model has been Added');
            return redirect('admin/model');
        } else {

            Session::flash('message','Model Not Added !!');
            return redirect('admin/model');
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


    public function update(Request $request)
    {
        $messages = [
            'image.mimes' => trans('validation.mimes'),
            'image.max.file' => trans('validation.max.file'),
        ];

        $driver = Driver::find($request->driver_id);
        if($request->hasFile('image'))
        {
            $validator = Validator::make($request->all(), [
                'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
            ],$messages);

            if ($validator->fails()) {
                return redirect('admin/cars/edit/'.$request->car_id)
                    ->withErrors($validator)
                    ->withInput();
            }

            $file = $request->file('image');
            $file_name = md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            if ($file->move('storage/cars/', $file_name)) {
                $driver->image = $file_name;
            }

            $driver->birthdate = $request->input('birthdate');
            $driver->marital_status = $request->input('marital_status');
            //Session::flash('alert-success','Driver & Car have been Edited');
            //$ad->update();
            if ( $driver->update()){
                $user = User::find($driver->user_id);
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->phone = $request->input('phone');
                $user->gender = $request->input('gender');
                $user->update();

                $car = Car::where('driver_id',$driver->user_id)->first();
                $car->mark = $request->input('brand_id');
                if($request->input('car_model'))
                    $car->car_model = $request->input('car_model');
                $car->plate =  $request->input('plate');
                $car->year =  $request->input('year');
                $car->color_id =  $request->input('color_id');
                $car->car_type =  $request->input('car_type');
                $car->update();
                $request->session()->flash('alert-success', __('message.car_and_driver_updated'));
                return redirect('admin/cars');
            }else{
                $request->session()->flash('alert-danger', __('message.car_not_updated'));
                return redirect('admin/cars');
            }
        }
        else
        {
            $driver->birthdate = $request->input('birthdate');
            $driver->marital_status = $request->input('marital_status');
            $driver->update();

            if ( $driver->update()){
                $user = User::find($driver->user_id);
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->phone = $request->input('phone');
                $user->update();

                $car = Car::where('driver_id',$driver->user_id)->first();
                $car->mark = $request->input('brand_id');
                if($request->input('car_model'))
                    $car->car_model = $request->input('car_model');
                $car->plate =  $request->input('plate');
                $car->year =  $request->input('year');
                $car->update();
                $request->session()->flash('alert-success', 'Driver & Car have been Edited');
                return redirect('admin/cars');
            }else{
                $request->session()->flash('alert-danger', 'Driver & Car have NOT been Edited');
                return redirect('admin/cars');
            }
        }
        //var_dump($request->all());exit();
        return redirect('admin/cars/edit/'.$request->car_id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car_model  $car_model
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {

        $car = Car::find($id);

        if($car )
        {
            $user = User::find($car->driver_id)->delete();
            $car->delete();
            $driver = Driver::where('user_id',$car->driver_id)->delete();
            //$del_driver = Driver::find($id)->delete();
        }

        return back()->with('success','Car deleted successfully');
    }
    public function destroyCarTypes($id)
    {
        $res = Car_type::find($id)->delete();
        //return back()->with('success','User deleted successfully');
        return redirect('admin/car-types');
    }

    public function checkRelatedDrivers(Request $request){
        $car = Car::where('car_type',$request->id)->first();
        if($car){
            return true;
        }
        else{
            $res = Car_type::find($request->id)->delete();
            return false;
        }
    }
}
