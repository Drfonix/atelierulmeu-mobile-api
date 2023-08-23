<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CarResource extends JsonResource
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
            "name" => $this->name,
            "category" => $this->category,
            "subcategory" => $this->subcategory,
            "registration_type" => $this->registration_type,
            "fuel_type" => $this->fuel_type,
            "vin_number" => $this->vin_number,
            "make" => $this->make,
            "model" => $this->model,
            "manufacture_year" => $this->manufacture_year,
            "tyre_size" => $this->tyre_size,
            "motor_power" => $this->motor_power,
            "cylinder_capacity" => $this->cylinder_capacity,
            "number_places" => $this->number_places,
            "max_per_mass" => $this->max_per_mass,
            "civ_number" => $this->civ_number,
            "description" => $this->description,
            "favorite" => $this->favorite,
            "images" => UserImageResource::collection(new UserImageResource($this->images)),
        ];
    }
}
