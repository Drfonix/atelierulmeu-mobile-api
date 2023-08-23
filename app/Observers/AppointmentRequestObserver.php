<?php

namespace App\Observers;

use App\Events\GeneralEvent;
use App\Models\AppointmentRequest;

class AppointmentRequestObserver
{
    /**
     * Handle the  "created" event.
     *
     * @param AppointmentRequest $appointmentRequest
     * @return void
     */
    public function created(AppointmentRequest $appointmentRequest)
    {
//        event(
//            new GeneralEvent(
//                $appointmentRequest->user_id, $appointmentRequest,
//                GeneralEvent::EVENT_TYPES["CREATED"],
//                GeneralEvent::MODEL_NAMES["APPOINTMENT_REQUEST"],
//            )
//        );
    }

    /**
     * Handle the  "updated" event.
     *
     * @param AppointmentRequest $appointmentRequest
     * @return void
     */
    public function updated(AppointmentRequest $appointmentRequest)
    {
//        event(
//            new GeneralEvent(
//                $appointmentRequest->user_id, $appointmentRequest,
//                GeneralEvent::EVENT_TYPES["UPDATED"],
//                GeneralEvent::MODEL_NAMES["APPOINTMENT_REQUEST"],
//            )
//        );
    }

    /**
     * Handle the  "deleted" event.
     *
     * @param AppointmentRequest $appointmentRequest
     * @return void
     */
    public function deleted(AppointmentRequest $appointmentRequest)
    {
//        event(
//            new GeneralEvent(
//                $appointmentRequest->user_id, $appointmentRequest,
//                GeneralEvent::EVENT_TYPES["DELETED"],
//                GeneralEvent::MODEL_NAMES["APPOINTMENT_REQUEST"],
//            )
//        );
    }

    /**
     * Handle the  "restored" event.
     *
     * @param AppointmentRequest $appointmentRequest
     * @return void
     */
    public function restored(AppointmentRequest $appointmentRequest)
    {
        //
    }

    /**
     * Handle the  "force deleted" event.
     *
     * @param AppointmentRequest $appointmentRequest
     * @return void
     */
    public function forceDeleted(AppointmentRequest $appointmentRequest)
    {
        //
    }
}
