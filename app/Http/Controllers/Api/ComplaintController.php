<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FailResource;
use App\Models\Complaint;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Models\ReplyComplaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    //add complaint by user with/without trip
    //[user] [driver]
    public function add_complaint(Request $request)
    {
        $request->validate([
            'complaint_number' =>'required',
            'user_id' =>'required'
        ]);
        $comp = new Complaint();
        $comp->user_id = $request->user_id;
        $comp->trip_id = 0;
        if($request->trip_id ) {
            $comp->trip_id = $request->trip_id;
        }
        $comp->ip_user = \Request::ip();
        $comp->complaint_number = (Complaint::latest()->first())?(Complaint::latest()->first()->complaint_number)+1:'1000';
        $comp->reason = $request->reason;
        if($request->status)
            $comp->status = $request->status;
        else
            $comp->status = 1;
        if($comp->save())
        {

            return response()->json(
                [
                    'message'=>'Add Complaint',
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>' تم إضافة شكوى',
                        'english_result'=>'complaint added',
                    ]
                ]
            );
        }
        else{
            $failResource = new FailResource('Add Complaint','Not added','لم يتم');
            return response()->json($failResource );
        }
    }

    //by user id
    public function complaints_list(Request $request)
    {
        $request->validate([
            'user_id' =>'required'
        ]);

        $comps = Complaint::with('feedback_reason')->where('user_id',$request->user_id)->get();
        return response()->json(
            [
                'message'=>'Complaints',
                'data'=> [
                    'complaints'=>$comps,
                ]
            ]
        );
    }

    //to driver
    public function complaints_list_on_driver(Request $request)
    {
        $request->validate([
            'driver_id' =>'required'
        ]);

        //$comps = Complaint::with('feedback_reason')->where('user_id',$request->user_id)->get();
        $comps = Complaint::with('feedback_reason')
            ->whereHas('trip',function($subQ)use ($request) {
            $subQ->where('driver_id', $request->driver_id);
        })->with('trip')->where('approved',1)->get();

        $complaintsMe = Complaint::with('feedback_reason')->where('user_id',$request->driver_id)->get();
        return response()->json(
            [
                'message'=>'Complaints on driver',
                'data'=> [
                    'complaints'=>$comps,
                    'complaints_me'=>$complaintsMe,
                ]
            ]
        );
    }

    //[user] [driver]
    public function add_reply(Request $request)
    {
        $request->validate([
            'complaints_id' =>'required',
            'user_id' =>'required',
            'text' =>'required'
        ]);
        $cr = new ReplyComplaint();
        $cr->text = $request->text;
        $cr->complaints_id = $request->complaints_id;
        $cr->user_id = $request->user_id;
        $cr->order = 1;
        if($cr->save())
        {
            return response()->json(
                [
                    'message'=>'Add reply to Complaint',
                    'data'=> [
                        'arabic_error'=>'',
                        'english_error'=>'',
                        'arabic_result'=>' تم إضافة رد',
                        'english_result'=>'Reply added',
                    ]
                ]
            );
        }
        else{
            return response()->json(
                [
                    'message'=>'Add reply to Complaint',
                    'data'=> [
                        'arabic_error'=>'لم يتم إضافة رد',
                        'english_error'=>'Reply Not added',
                        'arabic_result'=>'',
                        'english_result'=>'',
                    ]
                ]
            );
        }
    }

    public function complaints_with_replies(Request $request)
    {
        $request->validate([
            'complaints_id' =>'required'
        ]);

        $complaints_details_with_replies = ReplyComplaint::where('complaints_id',$request->complaints_id)->get();
        $complaint = Complaint::find($request->complaints_id);
        return response()->json(
            [
                'message'=>'Complaints with replies',
                'data'=> [
                    'Complaints_with_replies'=>$complaints_details_with_replies,
                    'complaint'=>$complaint
                ]
            ]
        );
    }

    public function show(Complaint $complaint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function edit(Complaint $complaint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateComplaintRequest  $request
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateComplaintRequest $request, Complaint $complaint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complaint $complaint)
    {
        //
    }
}
