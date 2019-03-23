<?php
namespace App\Services;

use App\Http\Controllers\LINENotifyController;
use App\PushSchedule;
use App\PushList;
use App\PushTmp;
use App\LINE_Notify_User;

use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;
use SoapBox\Formatter\Formatter;

use Carbon\Carbon;


class PushNotification
{
    public static function sendMsg($access_token, $msg)
    {
        $client = new GuzzleHttpClient();
        try {
            $response = $client->request('POST', 'https://notify-api.line.me/api/notify', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                ],
                'form_params' => [
                    'message' => $msg,
                ],
                'timeout' => 10,
            ]);
        } catch (GuzzleHttpTransferException $e) {
            $status = $e->getCode();
            if ($status == 400) {
                throw new \Exception('400 - Unauthorized request');
            } elseif ($status == 401) {
                throw new \Exception('401 -  Invalid access token');
            } elseif ($status == 500) {
                throw new \Exception('500 - Failure due to server error');
            } else {
                throw new \Exception('Processed over time or stopped');
            }
        }
        return $response;
    }

    public function getStatus($access_token)
    {
        $client = new GuzzleHttpClient();
        try {
            $response = $client->request('GET', 'https://notify-api.line.me/api/status', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                ],
                'timeout' => 10,
            ]);
            $response = $response->getBody()->getContents();
            $formatter = Formatter::make($response, Formatter::JSON);
            $json = $formatter->toArray();
            return $json;
        } catch (GuzzleHttpTransferException $e) {
            return $e;
        }
    }

    public function checkPushSchedule(){

        // 重置
        $query = PushSchedule::where([
            ['last_generator_at', '<=', Carbon::now()->startOfDay()],
            ['active_status', 2], // 啟動中
            ['status', 3], // 已產生
        ])->update(['status'=>1]);

        $query = PushSchedule::where([
            ['generator_time','<=',Carbon::now()->toTimeString()],
            ['active_status', 2], // 啟動中
            ['status',1] // 未處理
        ]);
        if($query->exists()){
            $collection = $query->select('id','push_msg','push_time_at','push_line_id')->get();
            PushSchedule::whereIn('id',$collection->pluck('id'))->update([
                'status'=>2,
                'generator_time' => Carbon::now()
            ]);
            $collection->map(function ($push_schedule) {
                PushList::create([
                    'push_schedule_id' => $push_schedule->id,
                    'push_line_id' => $push_schedule->push_line_id,
                    'push_msg' => $push_schedule->push_msg,
                    'push_at' => Carbon::now()->setTimeFromTimeString($push_schedule->push_time_at),
                    'status' => 1
                ]);
            });
            PushSchedule::whereIn('id', $collection->pluck('id'))->update(['status'=>3]);
        }
    }

    public function checkPushList(){
        $query = PushList::where([
            ['status',1] // 未處理
        ]);
        if($query->exists()){
            $collection = $query->select('id','push_msg','push_at','push_line_id')->with('line_user')->get();
            PushList::whereIn('id',$collection->pluck('id'))->update(['status'=>2]);
            $collection->map(function ($push_list) {
                PushTmp::create([
                    'group_id' => rand(1,10),
                    'push_list_id' => $push_list->id,
                    'push_msg' => $push_list->push_msg,
                    'push_token' => $push_list->line_user->access_token,
                    'push_at' => $push_list->push_at,
                    'status' => 1
                ]);
            });
            PushList::whereIn('id', $collection->pluck('id'))->update(['status'=>3]);
        }
    }

    public function checkPushTmp(){
        $query = PushTmp::where([
            ['push_at','<=',Carbon::now()],
            ['status',1] // 未處理
        ]);
        if($query->exists()){
            $ids = $query->select('id')->get()->pluck('id');
            // PushTmp::whereIn('id',$ids)->update(['status'=>2]);
            PushTmp::whereIn('id',$ids)->select('push_msg','push_token')->chunk(100,function($push_data_arr){
                foreach($push_data_arr as $data){
                    $this->sendMsg($data->push_token,$data->push_msg);
                }
            });
            PushTmp::whereIn('id',$ids)->update(['status'=>3]);
        }
    }
}
