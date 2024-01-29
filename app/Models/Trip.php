<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Trip extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'status','start_date','trip_number','driver_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function carType()
    {
        return $this->belongsTo(Car_type::class, 'car_type_id');
    }

    public function reasonText()
    {
        return $this->hasOne(Cancel_reason_text::class, 'trip_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'trip_id');
    }

    public function driverCompensation()
    {
        return $this->hasOne(DriverCompensation::class, 'trip_id');
    }

    //employees of a company that got a trip
    public function employees ()
    {
        return $this->belongsToMany(User::class,'employees_trips','trip_id','employee_id' );
    }

    public function trip_details()
    {
        return $this->hasOne(TripDetails::class, 'trip_id');
    }


    public function getlastNTrips($n)
    {
        return $this->/*latest()*/orderBy('id','desc')->take($n)->get();
    }

    public function getLastSerialNumber(){
        $latest =  DB::table('trips')->latest('serial_num')->first()->serial_num ?? 1000;
        $latest = (int)$latest+1;
        return $latest;
    }

    function truncate($string, $length) {
        return (strlen($string) > $length) ? substr($string, 0, $length )  : $string;
    }

    //per driver
    public function getTripsBetweenTwoDatesAll( $start_date, $end_date){
        $startDate = Carbon::createFromFormat('Y-m-d', $start_date);
        $endDate = Carbon::createFromFormat('Y-m-d',  $end_date);

        $x = DB::table('trips')
            ->select(DB::raw('count(*) as trip_count, driver_id'))
            //->select('driver_id')
            ->whereDate('start_date', '>=', $startDate )
            ->whereDate('start_date', '<=', $endDate )
            ->groupBy('driver_id')
            ->get();
        return $x;
    }
    //sum trip for users
    public function getTripsCountByUsers( ){

        $x = DB::table('trips')
            ->select(DB::raw('count(*) as trip_count, user_id'))
            //->select('driver_id')
            ->where('status','!=',5)
            ->groupBy('user_id')
            ->get();
        return $x;
    }

    //per Day
    public function getTripsBetweenTwoDatesPerDay( $start_date, $end_date ){
        $startDate = Carbon::createFromFormat('Y-m-d', $start_date);
        $endDate = Carbon::createFromFormat('Y-m-d',  $end_date);

        $x = DB::table('trips')
            ->select(DB::raw('count(*) as trip_count, DATE(start_date) as trip_day'))
            ->whereDate('start_date', '>=', $startDate )
            ->whereDate('start_date', '<=', $endDate )

            ->where('status', '!=',5 )
            ->groupBy('trip_day')
            ->get();
        return $x;
    }
    //per day cancelled
    public function getCancelledTripsBetweenTwoDatesPerDay( $start_date, $end_date ){
        $startDate = Carbon::createFromFormat('Y-m-d', $start_date);
        $endDate = Carbon::createFromFormat('Y-m-d',  $end_date);

        $x =DB::table('trips')
            ->select(DB::raw('count(*) as trip_count, DATE(created_at) as trip_day'))
            ->whereDate('created_at', '>=', $startDate )
            ->whereDate('created_at', '<=', $endDate )
            ->where('status', 5 )
            ->groupBy('trip_day')
            ->get();
        return $x;
    }

    //users
    public function getTripsBetweenTwoDates($user_id, $start_date, $end_date){
        $startDate = Carbon::createFromFormat('Y-m-d', $start_date);
        $endDate = Carbon::createFromFormat('Y-m-d',  $end_date);
        $results = $this->where('user_id',$user_id)->whereDate('start_date','>=', $startDate)->whereDate('start_date','<=', $endDate)->get();
        return $results;
    }

    public function getTripsBetweenTwoDatesDriver($driver_id, $start_date, $end_date){

        $startDate = Carbon::createFromFormat('Y-m-d', $start_date);
        $endDate = Carbon::createFromFormat('Y-m-d',  $end_date);
       $results = Trip::where('driver_id',$driver_id)->whereDate('start_date','>=', $startDate)->whereDate('start_date','<=', $endDate)->get();
        //$results = Trip::where('driver_id',$driver_id)->whereBetween(DB::raw('DATE(start_date)'), [$startDate, $endDate])->get();
        return $results;

    }

    public function getTripsWithDetailsBetweenTwoDatesDriver($driver_id, $start_date, $end_date){

        //$startDate = Carbon::createFromFormat('Y-m-d', $start_date);
       // $endDate = Carbon::createFromFormat('Y-m-d',  $end_date);
        $results = Trip::where('driver_id',$driver_id)->whereDate('created_at','>=', $start_date)->whereDate('created_at','<=', $end_date)->get();
        //$results = Trip::where('driver_id',$driver_id)->whereBetween(DB::raw('DATE(start_date)'), [$startDate, $endDate])->get();
        return $results;

    }

    public function changeTripStatus($status){

    }

    public function getDifferenceOfTimeInMinutes($start_time, $end_time)
    {
        //'2020-02-10 04:04:26'
        //'2020-02-11 04:36:56'
        $minutes = abs(strtotime($end_time) - strtotime($start_time)) / 60;

        return $minutes;
    }

    //morph...add note
    public function noteTrip()
    {
        return $this->morphOne(Note::class, 'notetable');
    }

    //get scheduled trips that happened after 1 hour
    public function getComingScheduledTrip(){
        $scheduled = $this->whereBetween('trip_date', [date('Y-m-d H:i:s'), Carbon::now()->addHour()])->where('is_scheduled',1)->get();
        return $scheduled;
    }

    public function pendingTrips(){
        $pTrips = $this->with('user')->whereIn('status',[0,6])->where('driver_id',0)->get();
        return $pTrips;
    }

    public function getScheduledTrip(){
        $sTrips = $this->with('user')->whereIn('status',[0,1])->where('is_scheduled',1)->get();
        return $sTrips;
    }

    public function getActiveTripByDriverId($driverId){
        $trip = Trip::with('driver','user')
            ->where(function($q) {
                $q->whereIn('status',[1,2,3])
                    ->where('is_scheduled',0);
            })
            ->orWhere(function($q1){
                $q1->whereIn('status',[2,3])
                    ->where('is_scheduled',1);
            })
            ->first();
        return $trip;
    }

}
