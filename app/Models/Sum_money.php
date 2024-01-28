<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sum_money extends Model
{
    use HasFactory;
    protected $fillable = [
        'balance',
        'amount',
        'work_day',
        'num_of_renew_requests',
        'driver_id'
    ];
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function driver_id() {
        $driver = $this->driver;
        return $driver->id;
    }

    public function sumMoneyAcheivedPerMonth($driver_id, $month, $year){
        $x = DB::select('select driver_id ,SUM(amount) as sum_amount
            from `sum_moneys`
            where `driver_id`='.$driver_id.' AND MONTH(work_day)='.$month.' AND YEAR(work_day)='.$year.' group by driver_id;');
        return $x;
    }

    public function driversBalancesToday(){

        $results = DB::table('sum_moneys')
            ->select(DB::raw('MAX(sum_moneys.id) as id ,work_day,amount ,driver_id,balance'))
            ->join('users','sum_moneys.driver_id','=','users.id')
            /*->join('drivers','drivers.user_id','=','users.id')*/
           /* ->where('is_connected',1)*/
            /*->whereDate('sum_moneys.created_at',Carbon::today())*/
            ->groupBy('driver_id')
            ->get();

        return $results;
    }
    public function driverBalance($driver_id){
            //get latest record
            $result = $this ->where('driver_id',$driver_id)
                ->latest()
                ->first();
        return $result;
    }

    public function updateMoney($driver_id, $amount){
        $x = Sum_money::where('driver_id',$driver_id)->latest()->first();
        if($x){
            $x->balance += $amount;
            $x->num_of_renew_requests++;
            $x->save();
        }
        else{
            //add new record for this driver
            $today = Carbon::now();
            $y = new Sum_money();
            $y->amount =  0;
            $y->driver_id =  $driver_id;
            $y->balance = $amount;
            $y->work_day = $today->toDateString();
            $y->num_of_renew_requests = 1;
            $y->save();
        }
    }
    public function balanceByDate($driver_id, $date){
        $result = $this ->where('driver_id',$driver_id)
            ->whereDate('work_day','<', $date)
            ->orderBy('work_day','DESC')
            ->first();
        return $result;
    }

}
