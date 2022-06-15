<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
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
            "statusCode" => $this->statusCode ?? 500,
            "message"     => $this->message ?? 'Something went wrong',
            "error"       => $this->error ?? null,
            "data"        => $this->data ?? null
        ];
    }
}
