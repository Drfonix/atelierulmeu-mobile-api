<?php

namespace App\Observers;

use App\Events\GeneralEvent;
use App\Models\Car;

class CarObserver
{
    /**
     * Handle the  "created" event.
     *
     * @param Car $car
     * @return void
     */
    public function created(Car $car)
    {
//        event(
//            new GeneralEvent(
//                $car->user_id, $car,
//                GeneralEvent::EVENT_TYPES["CREATED"],
//                GeneralEvent::MODEL_NAMES["CAR"],
//            )
//        );
    }

    /**
     * Handle the  "updated" event.
     *
     * @param Car $car
     * @return void
     */
    public function updated(Car $car)
    {
//        event(
//            new GeneralEvent(
//                $car->user_id, $car,
//                GeneralEvent::EVENT_TYPES["UPDATED"],
//                GeneralEvent::MODEL_NAMES["CAR"],
//            )
//        );
    }

    /**
     * Handle the  "deleted" event.
     *
     * @param Car $car
     * @return void
     */
    public function deleted(Car $car)
    {
//        event(
//            new GeneralEvent(
//                $car->user_id, $car,
//                GeneralEvent::EVENT_TYPES["DELETED"],
//                GeneralEvent::MODEL_NAMES["CAR"],
//            )
//        );
    }

    /**
     * Handle the  "restored" event.
     *
     * @param Car $car
     * @return void
     */
    public function restored(Car $car)
    {
        //
    }

    /**
     * Handle the  "force deleted" event.
     *
     * @param Car $car
     * @return void
     */
    public function forceDeleted(Car $car)
    {
        //
    }
}
