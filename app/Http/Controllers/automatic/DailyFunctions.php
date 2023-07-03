<?php

namespace App\Http\Controllers\automatic;

use App\Http\Controllers\Controller;
use App\Models\CronJob;
use App\Models\InsuranceModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Shetabit\Visitor\Models\Visit;

class DailyFunctions extends Controller
{
    public function index()
    {
        try {
            DB::beginTransaction();
            $total_visits=[];
            $windows=0;
            $Android=0;
            $Ios=0;
            $Mac=0;
            $other_devices=0;
            $yesterday=Carbon::yesterday();
            $visits = Visit::where('created_at', '>', $yesterday)->get();
            foreach ($visits as $visit){
                array_push($total_visits,$visit['ip']);
                if ($visit['platform']=='Windows'){
                    $windows=$windows+1;
                } elseif ($visit['platform']=='AndroidOS'){
                    $Android=$Android+1;
                } elseif ($visit['platform']=='Linux'){
                    $Ios=$Ios+1;
                } elseif ($visit['platform']=='OS X'){
                    $Mac=$Mac+1;
                }else{
                    $other_devices=$other_devices+1;
                }
            }
            $unique_visits=array_unique($total_visits);
            InsuranceModel::create([
                'unique_visits'=>sizeof($unique_visits),
                'total_visits'=>sizeof($total_visits),
                'windows'=>$windows,
                'Ios'=>$Ios,
                'Android'=>$Android,
                'Mac'=>$Mac,
                'other_devices'=>$other_devices,
                'created_at'=>$yesterday,
            ]);
            $records=Visit::all();
            foreach ($records as $record){
                $record->delete();
            }
            CronJob::create([
                'job'=>'site_visit_insurance_daily',
                'status'=>'success'
            ]);
            DB::commit();
        }catch (\Exception $exception){
            CronJob::create([
                'job'=>'site_visit_insurance_daily',
                'status'=>'failed'
            ]);
            DB::rollBack();
        }
    }
}
