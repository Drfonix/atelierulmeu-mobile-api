<?php

namespace App\Events;

use App\Http\Resources\AppointmentRequestResource;
use App\Http\Resources\CarResource;
use App\Http\Resources\NotificationResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeneralEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $model;
    protected $event;
    protected $modelName;
    protected $userId;

    public const EVENT_TYPES = [
        "CREATED" => "CREATED",
        "UPDATED" => "UPDATED",
        "DELETED" => "DELETED",
    ];

    public const MODEL_NAMES = [
        "CAR" => "Car",
        "NOTIFICATION" => "Notification",
        "APPOINTMENT_REQUEST" => "AppointmentRequest",
    ];

    /**
     * Create a new event instance.
     *
     * @param $userId
     * @param  $model
     * @param null $event
     * @param null $modelName
     */
    public function __construct($userId, $model, $event = null, $modelName = null)
    {
        $this->model = $model;
        $this->event = $event ?? $this::EVENT_TYPES["CREATED"];
        $this->modelName = $modelName ?? "";
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("user.{$this->userId}");
    }

    public function broadcastWith()
    {
        switch ($this->modelName) {
            case $this::MODEL_NAMES["CAR"]:
                $model = new CarResource($this->model);
                break;
            case $this::MODEL_NAMES["NOTIFICATION"]:
                $model = new NotificationResource($this->model);
                break;
            case $this::MODEL_NAMES["APPOINTMENT_REQUEST"]:
                $model = new AppointmentRequestResource($this->model);
                break;
            default:
                $model = [];
        }
        return [
            'model_name' => $this->modelName,
            'event' => $this->event,
            'model' => $model,
        ];
    }
}
