<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\InsuranceModel;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Shetabit\Visitor\Models\Visit;


class DashboardController extends Controller
{
    public function index()
    {

        $Ios = 0;
        $Android = 0;
        $windows = 0;
        $Mac = 0;
        $other_devices = 0;
        foreach (InsuranceModel::all() as $item) {
            $Ios = $Ios + $item['Ios'];
            $Android = $Android + $item['Android'];
            $windows = $windows + $item['windows'];
            $Mac = $Mac + $item['Mac'];
            $other_devices = $other_devices + $item['other_devices'];
        }
        $date = [];
        $val = [];
        //get insurance for chartJs
        $day_ago = '-15';
        $time = Carbon::now()->addDay($day_ago);
        $visit_insurance = InsuranceModel::where('created_at', '>', $time)->get();
        foreach ($visit_insurance as $item) {
            array_push($date, verta($item['created_at'])->format('%d %B'));
            array_push($val, $item['unique_visits']);
        }
        //today visit
        $today_visit_count = $this->today_visits();
        array_push($date, 'امروز');
        array_push($val, $today_visit_count);
        //
        $startWeek = convertShamsiToGregorianDate((new Verta())->startWeek());
        $startMonth = convertShamsiToGregorianDate((new Verta())->startMonth());
        $subTotalMonth = 0;
        $subMonth = Order::select('total_amount')->where('status','!=',0)->where('created_at', '>=', $startMonth)->get();
        //monthly sale
        foreach ($subMonth as $item) {
            $subTotalMonth = $subTotalMonth + $item['total_amount'];
        }
        //weekly sale
        $subTotalWeek = 0;
        $subWeek = Order::select('total_amount')->where('status','!=',0)->where('created_at', '>=', $startWeek)->get();
        foreach ($subWeek as $item) {
            $subTotalWeek = $subTotalWeek + $item['total_amount'];
        }
        //visit insurance
        $yesterdayVisits = $this->visits(Carbon::yesterday(),1);
        $this_month_Visits = $this->visits($startMonth,1);
        $total_visit = $this->visits(0,0);
        $date = json_encode($date, JSON_NUMERIC_CHECK);
        $val = json_encode($val, JSON_NUMERIC_CHECK);
        //Comments
        $comments = Comment::latest()->get()->count();
        return view('admin.dashboard.index', compact(
            'comments',
            'subTotalWeek',
            'subTotalMonth',
            'subTotalMonth',
            'visit_insurance',
            'date',
            'val',
            'Mac',
            'other_devices',
            'Ios',
            'Android',
            'windows',
            'today_visit_count',
            'yesterdayVisits',
            'this_month_Visits',
            'total_visit',
        ));
    }

    public function today_visits()
    {
        $today_visit = Visit::all();
        $today_visit_count = [];
        foreach ($today_visit as $today) {
            array_push($today_visit_count, $today['ip']);
        }
        $today_visit_count = array_unique($today_visit_count);
        return sizeof($today_visit_count);
    }

    public function visits($time,$param)
    {
        $count = 0;
        if ($param == 0) {
            $visits = InsuranceModel::all();

        } else {
            $visits = InsuranceModel::where('created_at', '>=', $time)->get();
        }
        foreach ($visits as $visit) {
            $count = $count + $visit['unique_visits'];
        }
        return $count;
    }
}
