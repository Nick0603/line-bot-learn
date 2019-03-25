<?php

namespace App\Console\Commands;

use App\Services\PushNotification;
use App\Services\DynamicNotification;

use Illuminate\Console\Command;

class CreatNotification extends Command
{
    private $push_service;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:dynamic_notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '建立動態的推播資料';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->dynamic_notification_service = new DynamicNotification();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dynamic_notification_service->createCountEndDateNoficaition();

    }
}
