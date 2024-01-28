<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\NotificationsController;
use App\Models\DriverAlert;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDriverAlertRequest;
use App\Http\Requests\UpdateDriverAlertRequest;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DriverAlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = __('page.Send_Warnings');
        $alerts = DriverAlert::all();
        $D = new User();
        $drivers = $D->getDrivers();
        return view('admin.send-alerts.index',compact('alerts','title','drivers'));
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
     * @param  \App\Http\Requests\StoreDriverAlertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $alert = new DriverAlert();
        $alert->text = $request->input('text');
        $alert->text_en = $request->input('text_en');
        $alert->driver_id = $request->input('driver_id');
        $alert->alert_type = $request->input('alert_type');

        if ($alert->save()) {
            //send notification
            $notificationObj = new NotificationsController();
            $data = [
                'trip_id'=> 0,
                'notification_type'=>'advertisement',
                'is_driver' =>1
            ];
            $body = $alert->text;
            $notificationObj->sendNotifications($alert->driver_id, "Warning", $body ,$data);
            Session::flash('alert-success',__('message.alert_driver'));
            return redirect('admin/send-alerts');
        } else {

            Session::flash('alert-success',__('label.not_added'));
            return redirect('admin/send-alerts');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DriverAlert  $driverAlert
     * @return \Illuminate\Http\Response
     */
    public function show(DriverAlert $driverAlert)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DriverAlert  $driverAlert
     * @return \Illuminate\Http\Response
     */
    public function edit(DriverAlert $driverAlert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDriverAlertRequest  $request
     * @param  \App\Models\DriverAlert  $driverAlert
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDriverAlertRequest $request, DriverAlert $driverAlert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DriverAlert  $driverAlert
     * @return \Illuminate\Http\Response
     */
    public function destroy(DriverAlert $driverAlert)
    {
        //
    }
}
