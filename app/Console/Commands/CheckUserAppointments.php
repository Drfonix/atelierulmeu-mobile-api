<?php

namespace App\Console\Commands;

use App\Jobs\SendUserAppointmentNotificationJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckUserAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user appointments and send notification';

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
            SendUserAppointmentNotificationJob::dispatch();
            Log::channel("job")->info("Job SendUserAppointmentNotificationJob was successfully");
        } catch (\Exception $exception) {
            Log::channel("job")->info(sprintf("Job SendUserAppointmentNotificationJob Error. Message: %s", json_encode($exception, JSON_THROW_ON_ERROR)));
        }

        $this->info('### END ###');
        return 0;
    }
}
