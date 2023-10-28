<?php

namespace App\Jobs;

use App\Models\Alert;
use App\Services\FirebaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/**
 * Class SendUserEventNotificationJob
 * @package App\Jobs
 */
class SendUserEventNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
//    public $timeout = 120;

    // Priority levels: high, default, low
    public $priority = 'default';

    /**
     * @var FirebaseService
     */
    protected FirebaseService $firebaseService;

    /**
     * Create a new job instance.
     *
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
        $weekPlus = Carbon::now()->addWeek()->startOfDay();
        $threeDaysPlus = Carbon::now()->addDays(3)->startOfDay();

        $daysPlusQuery = $this->getAlertsByDate($threeDaysPlus);
        $this->sendNotificationsByQuery($daysPlusQuery);

        $weekPlusQuery = $this->getAlertsByDate($weekPlus);
        $this->sendNotificationsByQuery($weekPlusQuery);

    }

    /**
     * @param $desiredDate
     * @return Builder
     */
    public function getAlertsByDate($desiredDate)
    {
        $now = Carbon::now()->startOfDay();
        $query = Alert::query()->with(["user", "car"])
            ->whereHas('user', function ($q){
                $q->whereNotNULL('users.device_token');
            })
            ->whereHas('car')
            ->whereDate("alert_date", "<=", $desiredDate)
            ->whereDate("alert_date", ">=", $now);
        return $query;
    }

    /**

     * @param Builder $query
     */
    public function sendNotificationsByQuery(Builder $query)
    {
        $query->chunk(500, function ($alerts){
            $messages = [];
            foreach ($alerts as $alert) {
                $notificationData = generate_alert_notification_data($alert);
                $message = $this->firebaseService->createMessage($notificationData["token"], $notificationData);
                $messages[] = $message;
            }
            $this->firebaseService->sendMessage($messages, true);
        });
    }
}
