<?php

namespace App\Services;

use App\Jobs\SendTextMessageJob;
use App\Models\AuthRequest;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function __construct()
    {
        //
    }

    public function sendTextMessage(AuthRequest $authRequest): void
    {

        if (!$authRequest->phone || !is_valid_romanian_phone_number($authRequest->phone)) {
            Log::channel("sms")->debug(sprintf('%s - Phone number is either invalid, or entirely missing', $authRequest->phone ?? "no phone"));
            return;
        }

        $message = $this->getMessageContents($authRequest->type, $authRequest->code);
        SendTextMessageJob::dispatchAfterResponse($authRequest, $message);
    }

    private function getMessageContents(string $type, string $code): string
    {
        $text = "Codul de autetificare in applicatia VehiGo este: " . $code;

        if($type === AuthRequest::TYPE_REGISTRATION) {
            $text = "Codul de inregistrare in applicatia VehiGo este: " . $code;
        } else if($type === AuthRequest::TYPE_CHANGE) {
            $text = "Codul penrtu a schimba numarul de telefon in applicatia VehiGo este: " . $code;
        }
        return $text;
    }

}
