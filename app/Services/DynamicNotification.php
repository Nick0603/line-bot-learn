<?php
namespace App\Services;
use Carbon\Carbon;
use App\PushList;

class DynamicNotification
{
    public function createCountEndDateNoficaition(){
        $end_date = Carbon::parse('2019/3/26');
        $today = Carbon::now()->startOfDay();
        if( PushList::where([
            ['push_at','>',Carbon::now()->startOfDay()],
            ['push_line_id','1'],
            ['push_msg','like','距離養貓計畫開始日還有%']
        ])->exists()){
            return False;
        };
        if($end_date->gt($today)){
            $count = $today->diffInDays($end_date);
            $msg = '距離養貓計畫開始日還有 '.$count.' 天哦！';
            PushList::create([
                'push_schedule_id' => 0,
                'push_line_id' => 1,
                'status' => 1,
                'push_msg' => $msg,
                'push_at' => Carbon::now()->setTime(5,0,0)
            ]);
        }else{
            return False;
        }
        return True;
    }
}
