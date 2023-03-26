<?php

namespace App\Netopia;

use Exception;
use Illuminate\Support\Facades\Log;

class SmsSender
{
    private $apiKey;
    private $secret;

    public function __construct()
    {
        $this->apiKey = config("netopia.api_key"); //value provided by suport@web2sms.ro
        $this->secret = config("netopia.secret_key"); // value provided by suport@web2sms.ro
    }

    public function sendSms(string $recipient, string $message)
    {
        if (config('app.env') === "local") {
            Log::channel('sms')->info("Skipping sms sending due to local env.");
            return [
                    "error" => [
                        "code" => 0,
                        "message" => 'All good, but skipping actual sms sending due to local env.'
                    ]
                ];
        }

        $nonce = time();
        $method = "POST";
        $url = "/prepaid/message";
        $sender = "";
        $visibleMessage = "VehiGo-Code"; //How the message do you want to appear on the interface. If empty string than $message value will beshown
        $scheduleDate = date("Y-m-d H:i:s"); //Format timestamp
        $validityDate = ''; //Format timestamp
        $callbackUrl = '';
        $string = $this->apiKey . $nonce . $method . $url . $sender .
            $recipient . $message . $visibleMessage . $scheduleDate .
            $validityDate . $callbackUrl . $this->secret;

        $signature = hash('sha512', $string);

        $data = array(
            "apiKey" => $this->apiKey,
            "sender" => $sender,
            "recipient" => $recipient,
            "message" => $message,
            "scheduleDatetime" => $scheduleDate,
            "validityDatetime" => $validityDate,
            "callbackUrl" => $callbackUrl,
            "userData" => "",
            "visibleMessage" => $visibleMessage,
            "nonce" => $nonce
        );
        $curlurl = 'https://www.web2sms.ro/prepaid/message';
        $ch = curl_init($curlurl);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $signature);
        $header = array();
        $header[] = 'Content-type: application/json';
        $header[] = 'Content-length: ' . strlen(json_encode($data, JSON_THROW_ON_ERROR));

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_THROW_ON_ERROR));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        try {
            $postResult = curl_exec($ch);
        } catch (Exception $ex) {
            Log::channel('sms')->error(sprintf('Unable to send sms , error=> %s', $ex->getMessage()));
        }

        if ($postResult === false) {
            Log::channel('sms')->error(sprintf('Something went wrong while sending the sms , error=> %s, error nr=> %s', curl_error($ch), curl_errno($ch)));
        }

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($postResult, 0, $header_size);
        $responseBody = substr($postResult, $header_size);

        curl_close($ch);

        return json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
    }
}
