<?php
namespace App\Services;
use Carbon\Carbon;
use App\PushList;
use Config;

class DynamicNotification
{
    public function createCountEndDateNoficaition(){
        $start_date = Carbon::parse('2019/3/26');
        $end_date = Carbon::parse('2019/7/15');
        $today = Carbon::now()->startOfDay();
        if( PushList::where([
            ['push_at','>',Carbon::now()->startOfDay()],
            ['push_line_id','1'],
            ['push_msg','like','%今天是阿宇當兵的第%']
        ])->exists()){
            return False;
        };
        if($start_date->lessThanOrEqualTo($today) && $today->lessThanOrEqualTo($end_date)){
            $count_start = $today->diffInDays($start_date) + 1;
            $count_end = $today->diffInDays($end_date) + 1;
            $greet_arr = Config::get('constants.good_morning_greet_arr');

            $greet_str = $greet_arr[ rand(0, count($greet_arr)-1)];
            $msg = $greet_str.'，今天是阿宇當兵的第 '.$count_start.'天，也是距離我們養貓計畫倒數的第 '.$count_end.' 天哦！(已經確認囉~)';
            PushList::create([
                'push_schedule_id' => 0,
                'push_line_id' => 1,
                'status' => 1,
                'push_msg' => $msg,
                'push_at' => Carbon::now()->setTime(8,0,0)
            ]);
        }else{
            return False;
        }
        return True;
    }

    public function createQuestionNoficaition(){
        $today = Carbon::now()->startOfDay();
        $today_date_str = $today->toDateString();
        if( PushList::where([
            ['push_at','>',Carbon::now()->startOfDay()],
            ['push_line_id','1'],
            ['push_msg','like',"%猜謎%"]
        ])->exists()){
            return False;
        };
        $greet_arr = Config::get('constants.question_greet_arr');
        $greet_str = $greet_arr[ rand(0, count($greet_arr)-1)];
        $data = Config::get('constants.daily_questions')[$today_date_str];
        $question_msg = $greet_str.'，來個小猜謎放鬆一下吧(我將在幾分鐘後跟你說答案哦~ )： '.$data['question'];
        PushList::create([
            'push_schedule_id' => 0,
            'push_line_id' => 1,
            'status' => 1,
            'push_msg' => $question_msg,
            'push_at' => Carbon::now()->setTime(15, 0, 0),
        ]);

        $answer_msg = '嘻嘻，冠樺今天謎語的答案是 '.$data['answer'];
        PushList::create([
            'push_schedule_id' => 0,
            'push_line_id' => 1,
            'status' => 1,
            'push_msg' => $answer_msg,
            'push_at' => Carbon::now()->setTime(15, 20, 0),
        ]);
    }
}
