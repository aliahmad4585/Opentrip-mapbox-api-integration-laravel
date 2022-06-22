<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "statusCode" => $this->resource->statusCode ?? 200,
            "message"     => $this->resource->message ?? "Success",
            "error"       => $this->resource->error ?? null,
            "data"        => $this->resource ?? null
        ];
    }
}
