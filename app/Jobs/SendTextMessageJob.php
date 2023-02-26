<?php

namespace App\Jobs;

use App\Models\AuthRequest;
use App\Models\SmsLog;
use App\Netopia\SmsSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTextMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $message;
    private string $phone;
    private AuthRequest $authRequest;

    /**
     * Create a new job instance.
     *
     * @param AuthRequest $authRequest
     * @param $message
     */
    public function __construct(AuthRequest $authRequest, $message)
    {
        $this->authRequest = $authRequest;
        $this->phone = $authRequest->phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $smsSender = new SmsSender();
        try {
            $result = $smsSender->sendSms($this->phone, $this->message);
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }
        SmsLog::create([
            'user_id' => $this->authRequest->user_id,
            'request_id' => $this->authRequest->id,
            'code' => $this->authRequest->code,
            'phone' => $this->phone,
            'message' => $this->message,
            'response' => $result,
        ]);
    }
}
