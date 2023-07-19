<?php

namespace App\Observers;

use App\Events\GeneralEvent;
use App\Models\Notification;

class NotificationObserver
{
    /**
     * Handle the  "created" event.
     *
     * @param Notification $notification
     * @return void
     */
    public function created(Notification $notification)
    {
        event(
            new GeneralEvent(
                $notification->user_id, $notification,
                GeneralEvent::EVENT_TYPES["CREATED"],
                GeneralEvent::MODEL_NAMES["NOTIFICATION"],
            )
        );
    }

    /**
     * Handle the  "updated" event.
     *
     * @param Notification $notification
     * @return void
     */
    public function updated(Notification $notification)
    {
        event(
            new GeneralEvent(
                $notification->user_id, $notification,
                GeneralEvent::EVENT_TYPES["UPDATED"],
                GeneralEvent::MODEL_NAMES["NOTIFICATION"],
            )
        );
    }

    /**
     * Handle the  "deleted" event.
     *
     * @param Notification $notification
     * @return void
     */
    public function deleted(Notification $notification)
    {
        event(
            new GeneralEvent(
                $notification->user_id, $notification,
                GeneralEvent::EVENT_TYPES["DELETED"],
                GeneralEvent::MODEL_NAMES["NOTIFICATION"],
            )
        );
    }

    /**
     * Handle the  "restored" event.
     *
     * @param Notification $notification
     * @return void
     */
    public function restored(Notification $notification)
    {
        //
    }

    /**
     * Handle the  "force deleted" event.
     *
     * @param Notification $notification
     * @return void
     */
    public function forceDeleted(Notification $notification)
    {
        //
    }
}
