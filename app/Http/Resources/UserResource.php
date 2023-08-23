<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 *
 * Class UserResource
 * @package App\Http\Resources
 */
class UserResource extends JsonResource
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
            "uuid" => $this->uuid,
            "username" => $this->username,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "phone" => $this->phone,
            "email" => $this->email,
            "cars" => CarResource::collection(new CarResource($this->cars)),
            "notifications" => NotificationResource::collection(new NotificationResource($this->notifications)),
            "appointments" => AppointmentRequestResource::collection(new AppointmentRequestResource(($this->appointments))),
//            "images" => $this->images,
        ];
    }
}
