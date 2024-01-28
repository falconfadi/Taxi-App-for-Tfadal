<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'سجل الأحداث';
        $logs = //Activity::all();
        DB::table('activity_log')->select('*')->get();
        $translations = ['created'=>__('label.create_item'),
            'deleted' =>__('label.delete_item'),
            ''=>'any',
            'updated'=>__('label.edit_item')];
        foreach ($logs as $log)
        {
            if(Admin::find($log->causer_id )){
                $names[$log->causer_id] = Admin::find($log->causer_id )->name;
                $arabicModels[$log->subject_type] = $this->getModelArabic($log->subject_type);
            }else{
                $names[$log->causer_id] ="---";
            }

        }
       // $arabic = $this->getModelArabic("App\Models\Car_model"/*$log->subject_type*/);
        //var_dump($arabic);exit();
        return view('admin.log.index',compact('logs','title','translations','names','arabicModels'));
    }
    public function getModelArabic($subject_type){
        $models = array('Admin' =>'مستخدمي لوحة التحكم' , 'Area'=>'', 'Border'=>'', 'Brand'=>'',
            'Cancel_reason'=>__('menus.Cancel_Reasons'), 'Cancel_reason_text'=>'','Car'=>__('menus.Cars') ,
            'Car_model'=>__('menus.Car_Models') , 'Car_type'=>__('menus.Car_types') , 'City'=>'','Complaint'=>'',
        'Driver' => __('menus.Drivers'), 'User'=> __('menus.Users'),'Offers'=>__('menus.Offers'));

        $modelFromDB = str_replace("App\\Models\\", "",$subject_type);
        foreach ($models as $model=>$value)
        {
            if($model==$modelFromDB) return $value;
        }
        return 'any';


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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
