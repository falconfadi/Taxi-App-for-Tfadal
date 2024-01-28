<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cancel_reason;
use App\Models\Complaint;
use App\Models\Driver;
use App\Models\DriverAlert;
use App\Models\ReplyComplaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ComplaintsController extends Controller
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
    public function index(Request $request)
    {
       $title =  __('menus.complaints');
//        $complaints = Complaint::with('user')->with('trip')->orderByDesc('id')->get();
//        return view('admin.complaints.index1',compact('complaints','title'));

        if ($request->ajax()) {
            $data = Complaint::select('id','user_id','trip_id')->get();
            //var_export($data);exit();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                   // $btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm">View</a>';
                   $btn = '<td> <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle " data-toggle="dropdown">
                                        <i data-feather="edit-2"  class="mr-50 "></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">
                                            <i data-feather="edit-2" class="mr-50"></i>
                                            <span>edit</span>
                                        </a>
                                    </div>
                                </div>  </td>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.complaints.index-datatable',compact('title'));
    }
    //send alert to driver
    public function verifyAndSendAlert($complaint_id){
        if(is_numeric($complaint_id)){
            $complaint = Complaint::whereHas('trip',function($subQ) {
                $subQ->with('driver');
            })->with('trip')->where('id',$complaint_id)->first();
            if($complaint){
                $complaint->approved = 1;
                $complaint->update();

                $alert = new DriverAlert();
                $alert->text = "نود إعلامك أنه تم ارسال شكوى عليك من قبل عميل ";
                $alert->text_en = "you have Alert from the company";
                $alert->driver_id = $complaint->trip->driver_id;
                $alert->alert_type = 1;

                if ($alert->save()) {
                    session()->flash('alert-success', 'تم تحذير السائق');
                    return redirect('/admin/complaints');
                }
                else{
                    session()->flash('alert-success', 'لم يتم');
                    return redirect('/admin/complaints');
                }
            }else{
                session()->flash('alert-danger', 'لايوجد بيانات');
                return redirect('/admin/complaints');
            }
        }
        else{
            redirect(404);
        }
    }

    public function store(Request $request)
    {
        $cr = new ReplyComplaint();
        $cr->text = $request->input('text');
        $cr->complaints_id = $request->input('complaints_id');
        $cr->user_id = 0;
        $cr->order = 1;

        if ($cr->save()) {
            Session::flash('alert-success',__('message.reply_added'));
            return redirect('admin/complaints/');
        } else {
            Session::flash('alert-success',__('message.reply_not_added'));
            return redirect('admin/complaints/');
        }
    }

    public function close($id)
    {
        //echo $id;
        $comp = Complaint::findOrFail($id);
        if($comp)
        {
            $data = array('is_open' => 0);
            $comp->update($data);
            session()->flash('alert-success', __('message.complaint_closed'));
            return redirect('/admin/complaints');
        }
        else{
            session()->flash('alert-success', __('message.complaint_not_closed'));
            return redirect('/admin/complaints');
        }
    }


}
