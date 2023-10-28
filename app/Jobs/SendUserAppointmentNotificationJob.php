<?php

namespace App\Jobs;

use App\Models\AppointmentRequest;
use App\Services\FirebaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class SendUserAppointmentNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
//    public $timeout = 120;

    // Priority levels: high, default, low
    public $priority = 'high';

    /**
     * @var FirebaseService
     */
    protected FirebaseService $firebaseService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->firebaseService = new FirebaseService();
        $now = Carbon::now()->startOfHour();
        $query = $this->getAppointmentsByDate($now);

        $query->chunk(500, function ($appointments){
            $messages = [];
            foreach ($appointments as $appointment) {
                $notificationData = generate_appointment_notification_data($appointment);
                $message = $this->firebaseService->createMessage($notificationData["token"], $notificationData);
                $messages[] = $message;
            }
            $this->firebaseService->sendMessage($messages, true);
        });
    }

    public function getAppointmentsByDate($date)
    {
        $now = Carbon::now();
        $nowPlusTwoHours = Carbon::now()->addHours(2);
        $query = AppointmentRequest::query()->with(["user"])
            ->whereHas('user', function ($q){
                $q->whereNotNULL('users.device_token');
            })
            ->whereDate("from", ">=", $now)
            ->whereDate("from", "<=", $nowPlusTwoHours);
        return $query;
    }
}
