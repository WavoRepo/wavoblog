<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'user' => parent::toArray($request),
            // Fetch details data
            'details' => ($this->exists) ? $this->getDetailsAttribute() : []
        ];
    }
}
