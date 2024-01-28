<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Balance;
use App\Models\Border;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use stdClass;

class BordersController extends Controller
{
    public function index()
    {
//        $locations = [
//            ['Mumbai', 19.0760,72.8777],
//            ['Pune', 18.5204,73.8567],
//            ['Bhopal ', 23.2599,77.4126],
//            ['Agra', 27.1767,78.0081],
//            ['Delhi', 28.7041,77.1025],
//            ['Rajkot', 22.2734719,70.7512559],
//        ];

        $areas = Area::all();
        $title = __("page.available_areas");
        return view('admin.borders.index',compact('title','areas'));
    }

    public function storeArea(Request $request)
    {
       // abort_unless(\Gate::allows('company_create'), 403);
        $area = new Area();//::orderBy('id', 'DESC')->first();
        $area->name = $request->name;
        $area->save();
        Session::flash('alert-success',__('message.area_added') );
        return redirect('admin/borders');
    }

    public function updateArea(Request $request)
    {
        $area = Area::find($request->area);
        $area->name = $request->name;
        $area->save();
        Session::flash('alert-success',__('message.area_edited') );
        return redirect('admin/borders');
    }

    public function add_markers(){

        $title = __('page.add-area');

        $virtualAreaName =  rand(1000,9999);
        $lastArea = Area::latest('id')->first();

        $newArea =  $lastArea->id+1;
        $markers = Border::where('area',$newArea)->get();
        if($markers)
        foreach ($markers as $x){
            $marker = Border::find($x->id);
            $marker->delete();
        }
        return view('admin.borders.add-markers',compact('title','virtualAreaName','newArea'));
    }

    public function test2(){

        return view('admin.borders.googleAutocomplete2'/*,compact('cars')*/);
    }


    public function storeMarkers(Request $request)
    {
        $latLng = $request->latLng;
        // echo $latLng;
        $x1 = substr($latLng, 8);
       $x2 = substr_replace($x1 ,"", -1);
       //echo $x2;
        $latLng = explode( ", ", $x2 );

        $marker = Border::where('order_',$request->id)->where('area',$request->area)->first();
        if($marker)
        {
            $marker->latitude = $latLng[0];
            $marker->longitude = $latLng[1];
            $marker->update();
        }
        else
        {
            $x = new Border();
            $x->latitude = $latLng[0]; //This Code coming from ajax request
            $x->longitude= $latLng[1]; //This Chief coming from ajax request
            $x->order_= $request->id; //This Chief coming from ajax request
            $x->area= $request->area;
            $x->name = 3;
            $x->save();
        }

    }

    public function edit($id){
        $title = __('label.edit-area');
        //var_dump($id);exit();
        $virtualAreaName =  rand(1000,9999);
        $area = Area::find($id);

        //$lastArea = Area::latest('id')->first();

       // $newArea =  $lastArea->id+1;

        $markers = Border::where('area',$id)->get();
        $latitudes = $longitude = $markersToView =  array();
        if($markers)
            foreach ($markers as $marker){
                //$marker = Border::find($x->id);
                //$marker->delete();
                $item =   new stdClass();
                $item->id = $marker->order_;
                $item->lat= $marker->latitude;
                $item->lon = $marker->longitude;

                array_push($markersToView,$item);
                array_push($longitude,$marker->longitude);
            }
        //var_dump($latitudes);exit();
            //print_r($markersToView);exit();
        return view('admin.borders.edit-markers',compact('title','virtualAreaName','markersToView','area'));
    }

    public function updateMarkers(Request $request)
    {
        $latLng = $request->latLng;
        // echo $latLng;
        $x1 = substr($latLng, 8);
        $x2 = substr_replace($x1 ,"", -1);
        //echo $x2;
        $latLng = explode( ", ", $x2 );

        $marker = Border::where('order_',$request->id)->where('area',$request->area)->first();
        if($marker)
        {
            $marker->latitude = $latLng[0];
            $marker->longitude = $latLng[1];
            $marker->update();
        }
//        else
//        {
//            $x = new Border();
//            $x->latitude = $latLng[0]; //This Code coming from ajax request
//            $x->longitude= $latLng[1]; //This Chief coming from ajax request
//            $x->order_= $request->id; //This Chief coming from ajax request
//            $x->area= $request->area;
//            $x->name = 3;
//            $x->save();
//        }

    }

    public function destroy($id){
        $area = Area::find($id);
        if($area )
        {
            $markers = Border::where('area',$id)->get();
            if($markers)
                foreach ($markers as $x){
                    $marker = Border::find($x->id);
                    $marker->delete();
                }
            $area->delete();
        }
        return back()->with('success',' deleted successfully');
    }
}
