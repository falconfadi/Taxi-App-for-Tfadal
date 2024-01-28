<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $title = "Permissions";
        $perms  = Permission::where('guard_name','admin')->get();

        $mainPerms  = Permission::where('guard_name','admin')->where('main_group',1)->get();
        $mainGoup = array();
        foreach ($mainPerms as $perm){
            $mainGoup[$perm->id] = $perm->name;
        }
        $role = new RoleController();
        $types = $role->types;


        return view('admin.permissions.index',compact('title','perms','mainGoup','mainPerms','types'));
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
        $permission = Permission::create(['guard_name' => 'admin',
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'main_group' => $request->main_group,
            'group_id' => $request->group_id,
            'type' => $request->type,
            'guard_name' => 'admin'
            ]);


            Session::flash('alert-success',__('message.new_faqs_added'));
            return redirect('admin/permissions');

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
        $title = __('label.edit_faq');
        $perm_ = Permission::findById($id);
        $mainPerms  = Permission::where('guard_name','admin')->where('main_group',1)->get();
        $mainGoup = array();
        foreach ($mainPerms as $perm){
            $mainGoup[$perm->id] = $perm->name;
        }
//        $types = array(
//            '1'=> 'add' ,'2'=> 'edit' ,'3'=> 'delete', '4'=> 'view', '5'=> 'print', '6'=> 'freeze', '7'=> 'change password' ,
//            '8'=> 'final delete' , '9'=> 'add note'
//        , '10' =>'verify', '11' =>'send notification' ,'12'=> 'send sms'
//        );
        $role = new RoleController();
        $types = $role->types;
        //var_dump($perm);exit();
        return view('admin.permissions.edit',compact('perm_','title','mainGoup','mainPerms','types'));
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
        $perm_ = Permission::findById($request->id);
        $perm_->name = $request->name;
        $perm_->name_ar = $request->name_ar;
        $perm_->main_group = $request->main_group;
        $perm_->group_id = $request->group_id;
        $perm_->type = $request->type;
        $perm_->guard_name = 'admin';
        $perm_->save();
        Session::flash('alert-success',__('message.new_faqs_added'));
        return redirect('admin/permissions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = Permission::findById($id)->delete();
//        if ($res){
//            Session::flash('alert-success','Offer Deleted !!');
//            return redirect('admin/offers');
//        }
        return back()->with('success','Faq deleted successfully');
    }
}
