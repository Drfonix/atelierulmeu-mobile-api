<?php

namespace App\Observers;

use App\Events\GeneralEvent;
use App\Models\Alert;

class AlertObserver
{
    /**
     * Handle the  "created" event.
     *
     * @param Alert $alert
     * @return void
     */
    public function created(Alert $alert)
    {
//        event(
//            new GeneralEvent(
//                $alert->user_id, $alert,
//                GeneralEvent::EVENT_TYPES["CREATED"],
//                GeneralEvent::MODEL_NAMES["NOTIFICATION"],
//            )
//        );
    }

    /**
     * Handle the  "updated" event.
     *
     * @param Alert $alert
     * @return void
     */
    public function updated(Alert $alert)
    {
//        event(
//            new GeneralEvent(
//                $alert->user_id, $alert,
//                GeneralEvent::EVENT_TYPES["UPDATED"],
//                GeneralEvent::MODEL_NAMES["NOTIFICATION"],
//            )
//        );
    }

    /**
     * Handle the  "deleted" event.
     *
     * @param Alert $alert
     * @return void
     */
    public function deleted(Alert $alert)
    {
//        event(
//            new GeneralEvent(
//                $alert->user_id, $alert,
//                GeneralEvent::EVENT_TYPES["DELETED"],
//                GeneralEvent::MODEL_NAMES["NOTIFICATION"],
//            )
//        );
    }

    /**
     * Handle the  "restored" event.
     *
     * @param Alert $alert
     * @return void
     */
    public function restored(Alert $alert)
    {
        //
    }

    /**
     * Handle the  "force deleted" event.
     *
     * @param Alert $alert
     * @return void
     */
    public function forceDeleted(Alert $alert)
    {
        //
    }
}
