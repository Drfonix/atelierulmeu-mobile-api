<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "status" => $this->status,
            "car_plate_number" => $this->car_plate_number,
            "client_name" => $this->client_name,
            "car_make_model" => $this->car_make_model,
            "phone" => $this->phone,
            "from" => $this->from,
            "to" => $this->to,
            "duration" => $this->duration,
            "meta_data" => $this->meta_data,
            "requested_services" => $this->requested_services,
            "service_data" => $this->service_data,
        ];
    }
}
