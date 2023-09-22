<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AlertService
{

    /**
     * Creates an user alert from document data
     *
     * @param User $user
     * @param $documentData
     */
    public function createAlertFromUserDocument(User $user, $documentData)
    {
        try {
            if(array_key_exists('meta_data', $documentData)) {
                $metaData = $documentData['meta_data'];
                $expirationDate = null;
                $carId = null;
                $type = null;
                if(array_key_exists('expiration_date', $metaData)) {
                    $expirationDate = $metaData["expiration_date"];
                }
                if(array_key_exists('type', $documentData)) {
                    $type = $documentData["type"];
                }
                if(array_key_exists('car_id', $documentData)) {
                    $carId = $documentData["car_id"];
                }

                if($expirationDate && $carId && $type) {
                    $alertData = [
                        'type' => $type,
                        'alert_date' => $expirationDate,
                        'car_id' => $carId,
                        'user_id' => $user->id,
                        'recurrent' => 'no'
                    ];
                    Alert::create($alertData);
                }

            }

        } catch (\Exception $ex) {
            Log::channel('user')->info(sprintf("Error on alert creation. User id: %s", $user->id));
        }
    }
}
