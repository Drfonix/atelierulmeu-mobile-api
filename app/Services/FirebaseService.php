<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;

/**
 * Class FirebaseService
 * @package App\Services
 */
class FirebaseService
{

    /**
     * @var Messaging
     */
    protected Messaging $notification;

    public function __construct()
    {
        $this->notification = Firebase::messaging();
    }

    public function sendMessage($message, $multiple = false)
    {
        $messageResp = null;
        try {
            if($message) {
                if($multiple) {
                    $messageResp = $this->notification->sendAll($message);
                } else {
                    $messageResp = $this->notification->send($message);
                }
                Log::channel("firebase")->info(sprintf("Notification was sent %s", json_encode($messageResp, JSON_THROW_ON_ERROR)));
            }
        } catch (\Exception $exception) {
            Log::channel("firebase")->error(sprintf("Error sending notification: Message: %s",json_encode($message, JSON_THROW_ON_ERROR)));
        }
        return $messageResp;

    }


    public function createMessage($token, $data)
    {
        $message =  null;
        $notificationObject = FirebaseNotification::fromArray([
            "title" => $data["title"],
            "body" => $data["body"]
        ]);

        if($token) {
            $message = CloudMessage::fromArray([
                "token" => $token,
                "name" => 'Test',
                "data" => [],
                "notification" => $notificationObject,
//            "android" => [],
//            "webpush" => [],
//            "apns" => [],
                "fcm_options" => ["analytics_label" => "vehigo-mobile"],
                "ttl" => 3600 * 24,
            ]);
        }
        return $message;
    }

}
