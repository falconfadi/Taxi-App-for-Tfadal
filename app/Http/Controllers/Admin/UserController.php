<?php

namespace App\Http\Controllers\Admin;
use Yajra\DataTables\DataTables;


use App\Models\Career;
use App\Models\Driver;
use App\Models\FreezeReason;
use App\Models\Kpi_users;
use App\Models\Note;
use App\Models\Offers;
use App\Models\Trip;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $title = __('menus.Users');
        $u = new User();
        //$users = User::where('is_driver',0)->get();
        $freezeReason = new FreezeReason();
        $users = $u->getAllUsers();
        $careers = Career::all();
        $freezeReason = new FreezeReason();
        $permissionsNames = $this->permissionsNames;
        $isAdmin = $this->isAdmin ;
        $trip = new Trip();
        $trips = $trip->getTripsCountByUsers();
        if ($request->ajax()) {
            //$data = User::select('*');
            $data = $u->getAllUsers();

            return Datatables::of($data)
                ->addIndexColumn()
                //$user is row item from $data
                ->addColumn('action', function($user) use($permissionsNames, $isAdmin, $freezeReason) {

                    $btn = '
                        <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        '.__('page.Actions').'
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenu2">';
                    if(in_array('edit_user',$permissionsNames) || $isAdmin) {
                        $btn .= '   <a class="dropdown-item" href="' . url('admin/users/edit/' . $user->id) . '">
                                        <i data-feather="edit-2" class="mr-50"></i>
                                        <span>' . __('page.Edit') . '</span>
                                    </a>';
                    }
                    if(in_array('change_password_user',$permissionsNames) || $isAdmin) {
                        $btn .= '    <a class="dropdown-item " href="'.url('admin/users/change_password/'.$user->id).'">
                                            <i data-feather="eye-off" class="mr-50"></i>
                                            <span>'.__('menus.change_password').'</span>
                                        </a>';
                    }
                    if(in_array('freeze_user',$permissionsNames) || $isAdmin) {
                        if($freezeReason->isFreezed($user->id)==0){
                        $btn .= '<a class="dropdown-item freeze-button"  data-toggle="modal" data-target="#new-folder-modal"  data-value="{{$user->id}}">
                                            <i data-feather="stop-circle" class="mr-50"></i>
                                            <span>'.__('page.Freeze').'</span>
                                        </a>';
                        }else{
                            $btn .= '<a class="dropdown-item" href="'.url('admin/users/unfreeze/'.$user->id).'">
                                                <i data-feather="stop-circle" class="mr-50"></i>
                                                <span>'.__('page.Un_Freeze').'</span>
                                            </a>';
                        }
                    }
                    if(!$user->note)
                    if(in_array('add_note_user',$permissionsNames) || $isAdmin) {
                        $btn .= ' <a class="dropdown-item add_note" href="#" data-toggle="modal" data-target="#inlineForm" data-id="'.$user->id.'">
                                        <i data-feather="clipboard"></i>
                                        <span>'.__('label.add_note').'</span>
                                    </a>';
                    }
                    $btn .= '<a class="dropdown-item add-career-button"  data-toggle="modal" data-target="#add-career-modal"  data-value="'.$user->id.'">
                                <i data-feather="stop-circle" class="mr-50"></i>
                                <span>إضافة مهنة</span>
                            </a>';
                    //
                    if(in_array('delete_user',$permissionsNames) || $isAdmin) {
                        $btn .= '<a class="dropdown-item confirm-text" href="'.url('admin/users/delete/'.$user->id).'">
                                    <i data-feather="trash" class="mr-50"></i>
                                    <span>'.__('page.Delete').'</span>
                                </a>';
                    }

                    $btn .= '
                          </div>
                        </div>';
                    return $btn;
                })
                ->addColumn('gender', function($user)  {
                    $gender = ($user->gender!=0)?($user->gender==1)?__('page.male'):__('page.female'):'';
                    return $gender;
                })
                ->addColumn('sum_trip_acheived', function($user) use($trips) {
                    $sumOftrips = $trips->filter(function ($userTrip)use($user) {
                    return $userTrip->user_id==$user->id;
                })->first()->trip_count??0;
                    return $sumOftrips;
                })

                ->rawColumns(['action','gender'])
                ->make(true);
        }
        return view('admin.users.index1',compact('users','title','careers'));
    }

//    public function index()
//    {
//        $title = __('menus.Users');
//        $u = new User();
//        //$users = User::where('is_driver',0)->get();
//        $freezeReason = new FreezeReason();
//        $users = $u->getAllUsers();
//
//        $U = new User();
//        $notAvailableDriversIds = $U->getUserIdswithActiveTrip();
//
//        $careers = Career::all();
//        $trip = new Trip();
//        $trips = $trip->getTripsCountByUsers();
//
//        $sumOftrips = array();
//        foreach ($users as  $user){
//            $sumOftrips[$user->id] = $trips->filter(function ($userTrip)use($user) {
//                return $userTrip->user_id==$user->id;
//            })->first()->trip_count??0;
//        }
//        return view('admin.users.index',compact('users','freezeReason','notAvailableDriversIds','title','careers','sumOftrips'));
//    }

    public function view($id)
    {
        $user = User::findOrFail($id);
        //$x = Kpi_users::where('user_id',$user->id)->first();
        //$sumTripsAcheived = ($x)?$x->sum_trip_acheived:0;

        $tripsCount = 0;
        if($user)
        {
            $trips = Trip::where('user_id',$id)->get();
            if(count($trips)>0){
                $tripsCount = count($trips);
            }
            $off = new Offers();
            $offers = $off->allOffersByUserId($user->id);
            //$offers = $user->offers;
            return view('admin.users.view',compact('user','tripsCount','offers'));
        }
    }

    public function edit($id)
    {
        $title = __('menus.Edit_User');
        $user = User::findOrFail($id);
        //print_r($user);exit();
        return view('admin.users.edit',compact('user','title'));
    }

    public function userTrips($id)
    {
        $title = __('menus.user_trips');
       // $user = User::findOrFail($id);
        $trips = Trip::where('user_id',$id)->get();
        $tripCtrl = new TripController();
        $status = $tripCtrl->status;
        return view('admin.users.user_trips',compact('trips','title','status'));
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
        $messages = [
            'image.mimes' => trans('validation.mimes'),
            'image.max.file' => trans('validation.max.file'),
        ];
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
           /* 'password' => 'min:7',*/
        ],$messages);

        if ($validator->fails()) {
            return redirect('admin/users/edit/'.$request->user_id)
                ->withErrors($validator)
                ->withInput();
        }
        $user = User::find($request->user_id);

        $user->name = $request->input('name');
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->gender = $request->input('gender');
       //update morph
        $note = $user->note();
        $note->note = $request->input('note');
        $user->note()->update(['note'=>$request->input('note')]);


        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $file_name = md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();
            if ($file->move('storage/users/', $file_name)) {
                $user->image = $file_name;
            }
        }
        if ( $user->update()){
            $request->session()->flash('alert-success', __('message.user_updated'));
            return redirect('admin/users');
        }else{
            $request->session()->flash('alert-danger', __('message.user_not_updated'));
            return redirect('admin/users');
        }
    }

    public function changePassword($id)
    {
        $title = __('menus.change_password');
        $user = User::findOrFail($id);
        return view('admin.users.changePassword',compact('user','title'));
    }

    public function changePasswordUpdate(Request $request)
    {
        $user = User::findOrFail($request->id);
        if($user)
        {
            $nums = rand(0001,9999);
            $capitalString = "ABCDEFGHIJKLMNOPQRSTUVWZYZ";
            $smallString = "abcdefghijklmnopqrstuvwxyz";
            $specialCharacters = "@#$%";
            $capital = $capitalString[rand(0, strlen($capitalString)-1)];
            $small = $smallString[rand(0, strlen($smallString)-1)];
            $special = $specialCharacters[rand(0, strlen($specialCharacters)-1)];

            $password = $capital.$small.$nums.$special.$special;
            $hashedPassword = Hash::make($password);
            $user->password = $hashedPassword;
            $user->save();

            $sendSMS = new SendSMSController();
            $msg = 'New%20Password:%20' . $password;
            $sendSMS->send($msg,$user->phone );
            session()->flash('alert-success', __('message.password_changed'));
            return redirect('/admin/users');

        }
        else{
            session()->flash('alert-danger', __('message.No_data'));
            return redirect('/admin/users/change_password/'.$request->id);
        }
    }

    public function addNote(Request $request)
    {
        $user = User::find($request->user_id);
        if($user)
        {
            $note = new Note();
            $note->note = $request->note;
            $user->note()->save($note);

            session()->flash('alert-success', __('message.note_added'));
            return redirect($request->back_url);
        }
        else{
            session()->flash('alert-danger', __('message.No_data'));
            return redirect('/admin/users/');
        }
    }

    public function freeze(Request $request)
    {
        $user = User::findOrFail($request->user_id_);
        if($user)
        {
//            $data = array('freeze' => 1);
//            $user->update($data);

            FreezeReason::create(['user_id'=>$request->user_id_, 'reason'=>$request->reason,'is_freeze'=>1]);

            session()->flash('alert-success', __('message.user_freezed'));
            return redirect('/admin/users');

        }
        else{
            session()->flash('alert-success', 'No Data');
            return redirect('/admin/users');
        }
    }

    public function unfreeze($id)
    {
        $user = User::findOrFail($id);
        if($user)
        {
//            $data = array('freeze' => 0);
//            $user->update($data);

            FreezeReason::create(['user_id'=>$id, 'reason'=>'','is_freeze'=>0]);

            session()->flash('alert-success', 'تم إلغاء تجميد العميل');
            return redirect('/admin/users');

        }
        else{
            session()->flash('alert-success', 'No Data');
            return redirect('/admin/users');
        }
    }
    public function addCareer(Request $request)
    {
        $user = User::find($request->userId);
        if($user)
        {
            $data = ['career_id'=>$request->career_id];
            $user->update($data);

            session()->flash('alert-success', __('message.career_added'));
            return redirect('/admin/users');

        }
        else{
            session()->flash('alert-danger', 'No Data');
            return redirect('/admin/users');
        }
    }


    public function  search (Request $request){
        //ajax search
        $title = __('label.user_search');
        $U = new User();

        $freezeReason = new FreezeReason();
        $name = $request->get('name');
        $gender = $request->get('gender');
        //$verified = $request->get('verified');
        $has_trip = $request->get('has_trip');
        if ($request->ajax()) {
            $query = User::query();
            if($name != '')
                $query->where('name',$name );
            if($gender != 0)
                $query->where('gender',$gender );

            if($has_trip != 2){
                if($has_trip == 1){
                    $query->whereHas('tripsDriver',function($subQ) {
                        $subQ->whereIn('status',[1,2,3]);
                    });
                }else{
                    $query->whereHas('tripsDriver',function($subQ) {
                        $subQ->whereNotIn('status',[1,2,3]);
                    });
                }
            }
            $data = $query->where('is_driver',0)->get();
            $output = '';
            if (count($data) > 0) {
                $output = '';
                foreach ($data as $user) {
                    $output .= '<tr>

                    <td><a target="_blank"  href="'.url('admin/users/view/'.$user->id).'">'.$user->name.'</a></td>';
                    $output .= '<td>'.$user->phone.'</td>';
                    $gender = ($user->gender!=0)?($user->gender==1)?__('page.male'):__('page.female'):'';
                    $output .= '<td>'.$gender.'</td>';

                    $output .= '<td>'.$user->address.'</td>';

                    $output .= '<td>'.$user->created_at.'</td>';
                    if( $this->isAdmin) {
                        $output .= '<td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                    <i data-feather="more-vertical"></i>
                                     <i style="font-size:14px" class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">

                                    <a class="dropdown-item" href="' . url('admin/users/view/' . $user->id) . '">
                                        <i data-feather="eye" class="mr-50"></i>
                                        <span>' . __("page.View") . '</span>
                                    </a>';
                        $output .= '<a class="dropdown-item cancel-trip" data-toggle="modal" data-target="#cancel-trip-with-reason"  data-value="' . $user->id . '"  >
                                            <i data-feather="stop-circle" class="mr-50"></i>
                                            <span>' . __('page.Cancel') . '</span>
                                        </a>
                                        <a class="dropdown-item add_note" href="#" data-toggle="modal" data-target="#inlineForm" data-id="' . $user->id . '">
                                            <i data-feather="clipboard"></i>
                                            <span>' . __('label.add_note') . '</span>
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <i data-feather="trash" class="mr-50"></i>
                                            <span>' . __('page.Delete') . '</span>
                                        </a>
                                    </div>
                                </div>
                        </td>';
                    }
                    $output .= '</tr>';
                }

            } else {
                $output .= '<li class="list-group-item">' . 'No results' . '</li>';
            }
            return $output;
        }
        return view('admin.users.search',compact('title'));
    }


    public function destroy($id)
    {
        //$res = User::find($id)->delete();
        return back()->with('success','User deleted successfully');
    }
    public function finalDelete($id)
    {
        $res = User::find($id)->forcedelete();
        return redirect('admin/users');
    }

    public function test(){
        $password = 'Aa123456';
        $hashedPassword = Hash::make($password);
        echo $hashedPassword;
    }

}
