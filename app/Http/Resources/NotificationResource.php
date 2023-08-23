<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
          "id" => $this->id,
          "car_id" => $this->car_id,
          "type" => $this->type,
          "title" => $this->title,
          "message" => $this->message,
          "alert_date" => $this->alert_date,
          "expiration_date" => $this->expiration_date,
          "meta_data" => $this->meta_data,
        ];
    }
}
