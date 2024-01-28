<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PolicyController extends Controller
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
    public function edit()
    {
        $title = __('menus.policy');
        $policy = Policy::find(1);
        $term = Term::find(1);
        return view('admin.policy.edit',compact('policy','title','term'));
    }

    public function update(Request $request)
    {
        $policy = Policy::find(1);
        //var_dump($request->all());exit();
        $term = Term::find(1);
        $policy->arabic_privacy = $request->input('arabic_privacy');
        $policy->english_privacy = $request->input('english_privacy');

        $term->english_terms = $request->input('english_terms');
        $term->arabic_terms = $request->input('arabic_terms');
        $term->update();
        if ($policy->update()) {
            //$request->session()->flash('alert-success', __('Setting has been Edited'));
            Session::flash('alert-success',__('message.Terms_has_been_Edited'));
            return redirect('admin/privacy_policy');
        } else {
            Session::flash('alert-success',__('message.Terms_has_been_Edited'));
            return redirect('admin/privacy_policy');
        }

    }

    public function editWhoWeAre()
    {
        $title = __('label.who_we_are');
        $policy = Policy::find(1);
        $term = Term::find(1);
        return view('admin.who_we_are.edit',compact('policy','title','term'));
    }

    public function updateWhoWeAre(Request $request)
    {
        $policy = Policy::find(1);

        $policy->arabic_who_we_are = $request->input('arabic_who_we_are');
        $policy->english_who_we_are = $request->input('english_who_we_are');


        if ($policy->update()) {
            //$request->session()->flash('alert-success', __('Setting has been Edited'));
            Session::flash('alert-success',__('message.Terms_has_been_Edited'));
            return redirect('admin/who_we_are');
        } else {
            Session::flash('alert-success',__('message.Terms_has_been_Edited'));
            return redirect('admin/who_we_are');
        }

    }
}
