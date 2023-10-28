<?php

namespace App\Http\Resources;

use App\Models\Car;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CalendarEventResource extends JsonResource
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
            "section" => "EVENT",
            "type" => $this->type,
            "status" => $this->status,
            "car_plate_number" => $this->car->plate_number,
            "car_make_model" => $this->getCarMakeModel($this->car),
            "car_id" => $this->car->id,
            "car_category" => $this->car->category,
            "car_image_id" => $this->car->getCarFavoriteImageId(),
            "date" => $this->alert_date,
        ];
    }

    public function getCarMakeModel(Car $car)
    {
        return sprintf("%s %s", $car->make, $car->model);
    }
}
