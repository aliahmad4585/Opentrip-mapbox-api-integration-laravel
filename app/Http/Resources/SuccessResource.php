<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
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
            "statusCode" => $this->statusCode ?? 200,
            "message"     => $this->message ?? "Success",
            "error"       => $this->error ?? null,
            "data"        => $this->data ?? null
        ];
    }
}
