<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class CompaniesController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title =  __('menus.companies');
        $companies = Admin::with('company')->get();
        $driversIds = array();

        return view('admin.companies.index',compact('companies','title','driversIds'));
    }

    public function create(){
        $title = __('label.new_company');
        //$perms = Permission::where('guard_name','admin')->get();
       // $roles = Role::all();
        return view('admin.companies.create',compact('title'));
    }

    public function store(Request $request){
        $messages = [
            'email.unique' =>  __('message.email_used_before'),
            'name.string' => 'الاسم يجب أن يكون سلسلة محارف '
        ];
        $attribute = ['password'=>'كلمة المرور','name'=>'الاسم'];
        $validator = Validator::make($request->all(), [
            'phone' =>'required',
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => ['required'],
        ],$messages,$attribute);

        if ($validator->fails())
        {
           // Session::flash('alert-danger',$validator->errors());
            return redirect('admin/companies/create')->withErrors($validator)->withInput();
        }
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $user = Admin::create(array(
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password'=>Hash::make($request->password),
            'gender' => 1
        ));
        //add company
        $com = new Company();
        $com->name = $request->name;
        $com->admin_id = $user->id;
        $com->save();

        //add company-admin role
        $user->assignRole(["company_admin"]);

        Session::flash('alert-success','تم إضافة شركة جديد');
        return redirect('admin/companies');
    }
}
