<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class UserDocumentResource extends JsonResource
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
            "name" => $this->name,
            "type" => $this->type,
            "size" => $this->size,
            "h_size" => $this->h_size,
            "meta_data" => $this->meta_data,
        ];
    }
}
