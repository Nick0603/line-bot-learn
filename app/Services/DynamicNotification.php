<?php
namespace App\Services;
use Carbon\Carbon;
use App\PushList;

class DynamicNotification
{
    public function createCountEndDateNoficaition(){
        $start_date = Carbon::parse('2019/3/26');
        $end_date = Carbon::parse('2019/7/25');
        $today = Carbon::now()->startOfDay();
        if( PushList::where([
            ['push_at','>',Carbon::now()->startOfDay()],
            ['push_line_id','1'],
            ['push_msg','like','早安冠樺，今天是阿宇當兵的第%']
        ])->exists()){
            return False;
        };
        if($today->gt($start_date) && $end_date->gt($today)){
            $count_start = $today->diffInDays($start_date) + 1;
            $count_end = $today->diffInDays($end_date);
            $msg = '早安冠樺，今天是阿宇當兵的第 '.$count_start.'天，也是距離我們養貓計畫倒數的第 '.$count_end.' 天哦！';
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
