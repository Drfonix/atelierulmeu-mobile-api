<?php

namespace App\Console\Commands;

use App\Jobs\SendUserEventNotificationJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckUserEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user events and send notification';

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
        $this->info('### START ###');
        try {
            SendUserEventNotificationJob::dispatch();
            Log::channel("job")->info("Job SendUserEventNotificationJob was successfully");
        } catch (\Exception $exception) {
            Log::channel("job")->info(sprintf("Job SendUserEventNotificationJob Error. Message: %s", json_encode($exception, JSON_THROW_ON_ERROR)));
        }

        $this->info('### END ###');
        return 0;
    }
}
