<?php

namespace App\Http\Controllers\Admin;

use App\Charts\BarChart;
use App\Http\Controllers\Controller;

use App\Models\Kpi_users;
use App\Http\Requests\StoreKpi_usersRequest;
use App\Http\Requests\UpdateKpi_usersRequest;

class KpiUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = __('page.kpi_users');
        $kpi_users = Kpi_users::with('users')->get();
        //return view('admin.kpi.index',compact('kpi_users','title'));
        return view('admin.users_kpi.users',compact('kpi_users','title'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function create()
//    {
//    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreKpi_usersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKpi_usersRequest $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kpi_users  $kpi_users
     * @return \Illuminate\Http\Response
     */
//    public function show(Kpi_users $kpi_users)
//    {
//        //
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kpi_users  $kpi_users
     * @return \Illuminate\Http\Response
     */
//    public function edit(Kpi_users $kpi_users)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKpi_usersRequest  $request
     * @param  \App\Models\Kpi_users  $kpi_users
     * @return \Illuminate\Http\Response
     */
//    public function update(UpdateKpi_usersRequest $request, Kpi_users $kpi_users)
//    {
//        //
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kpi_users  $kpi_users
     * @return \Illuminate\Http\Response
     */
//    public function destroy(Kpi_users $kpi_users)
//    {
//        //
//    }
    public function x(){
        $user_id=23 ;$price=34;
        $this->sumTripCancelled($user_id);
    }

    public function sumMoneyPaid($user_id ,$price){
        $x = Kpi_users::where('user_id',$user_id)->first();
        if($x){
            $x->sum_money_paid = $x->sum_money_paid + $price;
            $x->update();
            return true;
        }
        else{
            $y = new Kpi_users();
            $y->sum_money_paid =  $price;
            $y->user_id =  $user_id;
            $y->save();
            return true;
        }
    }

    public function sumTripCancelled($user_id ){
        $x = Kpi_users::where('user_id',$user_id)->first();
        if($x){
            $x->sum_trip_cancelled += 1;
            $x->update();
            return true;
        }
        else{
            $y = new Kpi_users();
            $y->sum_trip_cancelled =  1;
            $y->user_id =  $user_id;
            $y->save();
            return true;
        }
    }

    public function sumTripAcheived($user_id ){
        $x = Kpi_users::where('user_id',$user_id)->first();
        if($x){
            $x->sum_trip_acheived += 1;
            $x->update();
            return true;
        }
        else{
            $y = new Kpi_users();
            $y->sum_trip_acheived =  1;
            $y->user_id =  $user_id;
            $y->save();
            return true;
        }
    }

    //chart
    public function best_users(BarChart $chart ){
        $title = $chartTitle = 'أفضل عشرة عملاء';
        $y = new Kpi_users();
        $best_users = $y->best_users(30);

        $days = array();$names = array();$tripsAcheived = array();
        foreach ($best_users as $user)
        {
            if($user->users){
                $names[] = $user->users->name;
                $tripsAcheived[] = $user->sum_trip_acheived;
            }

        }

        $titleY = __('label.sum_trip_acheived');
       // return view('admin.users_kpi.best_users',['title'=>$title, 'chart' => $chart->build(/*$names,'', $tripsAcheived, $titleY ,$chartTitle*/)]);
        return view('admin.users_kpi.best_users',['title'=>$title,'chart' => $chart->build($names,'', $tripsAcheived, $titleY ,$chartTitle)]);
    }
    public function best_users_table( ){
        $title = 'أفضل مئة عميل ';
        $y = new Kpi_users();
        $best_users = $y->best_users(100);
        //var_dump($best_users);exit();
        return view('admin.users_kpi.best_users_table',compact('best_users','title'));

    }



}
