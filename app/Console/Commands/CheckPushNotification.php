<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PushNotification;

class CheckPushNotification extends Command
{
    private $push_service;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:notification {method}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'method : checkPushSchedule,checkPushList,checkPushTmp';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->push_service = new PushNotification();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $method = $this->argument('method');
        if( $method == 'checkPushSchedule'){
            $this->line('checkPushSchedule');
            $this->push_service->checkPushSchedule();
        }else if( $method == 'checkPushList'){
            $this->line('checkPushList');
            $this->push_service->checkPushList();
        }else if( $method == 'checkPushTmp'){
            $this->line('checkPushTmp');
            $this->push_service->checkPushTmp();
        }else{
            $this->line('no match any method');
        }
    }
}
