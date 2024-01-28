<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vacancy;
use App\Models\Profile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;


class VacancyController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $vacancies = Vacancy::all();
        //var_dump();
        return view('admin.vacancy.index',compact('vacancies'));
    }
    public function create()
    {
        return view('admin.vacancy.create');

    }

    public function store(Request $request)
    {
        $setting = new Vacancy();

        $setting->Title = $request->input('Title');
        $setting->Department = $request->input('Department');
        $setting->Governorate = $request->input('Governorate');
        $setting->Code = $request->input('Code');
        $setting->Description = $request->input('Description');
        $setting->Qualifications = $request->input('Qualifications');
        //$setting->update();
        if ($setting->save()) {
            //$request->session()->flash('alert-success', __('Setting has been Edited'));
            Session::flash('message','New Vacancy has been Added');
            return redirect('admin/vacancies');
        } else {

            Session::flash('message','Vacancy Not Added !!');
            return redirect('admin/vacancies');
        }

    }
}
