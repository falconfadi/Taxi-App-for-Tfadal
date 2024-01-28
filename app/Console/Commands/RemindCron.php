<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\NotificationsController;
use App\Models\Alert;
use App\Models\Trip;
use Illuminate\Console\Command;

class RemindCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RemindForSceduled:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //return 0;
       // Alert::create(['text'=>'text','text_en'=>'text1']);
        \Log::info("Cron is working fine!");
        $x = new Trip();
        $trips = $x->getComingScheduledTrip();
        if(!empty($trips)){
            foreach ($trips as $trip){
                if($trip->driver_id){
                    $noti = new NotificationsController();
                    $data = [
                        'trip_id'=> $trip->id,
                        'notification_type'=>'New Trip',
                        'is_multiple'=>0,
                        'is_driver' =>1,
                        'add_features' =>1
                    ];
                    $noti->sendNotifications($trip->driver_id,"تذكير","تذكير بالرحلة المجدولة",$data);
                }
            }
        }
    }
}
