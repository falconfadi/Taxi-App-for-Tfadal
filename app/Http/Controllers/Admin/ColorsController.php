<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ColorsController extends Controller
{
    public function index()
    {
        $title = __("menus.Colors");
        $colors = Color::all();
        return view('admin.colors.index',compact('title','colors'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFaqRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //var_dump($request->all());
        $faq = new Color();
        $faq->color_ar = $request->input('color_ar');
        $faq->color_en = $request->input('color_en');


        if ($faq->save()) {
            Session::flash('alert-success',__('message.new_color_added'));
            return redirect('admin/colors');
        } else {

            Session::flash('message',__('message.color_not_added'));
            return redirect('admin/colors');
        }
    }

    public function destroy($id)
    {
        $res = Color::find($id)->delete();
        //return back()->with('success','User deleted successfully');
        return redirect('admin/colors');
    }
}
