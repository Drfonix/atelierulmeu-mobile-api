<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CalendarAppointmentResource extends JsonResource
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
            "section" => "APPOINTMENT",
            "title" => $this->title,
            "type" => "to be decided",
            "status" => $this->status,
            "car_plate_number" => $this->car_plate_number,
            "car_make_model" => $this->car_make_model,
            "date" => $this->from,
            "from" => $this->from,
            "to" => $this->to,
        ];
    }
}
