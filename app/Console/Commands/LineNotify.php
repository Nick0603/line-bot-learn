<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\LINE_Notify_User;
use App\Services\PushNotification;


class LineNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'line:push {msg}';

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
     * @return mixed
     */
    public function handle()
    {
        $msg = $this->argument('msg');
        if(empty($msg)){
            $msg = '測試';
        }
        $users = LINE_Notify_User::getAllToken(); // LINE Notify Users
        foreach ($users as $key => $at) {
            PushNotification::sendMsg($at, $msg);
            // LINE 限制一分鐘上限 1000 次，做一些保留次數
            if (($key + 1) % 950 == 0) {
                sleep(62);
            }
        }
    }
}
