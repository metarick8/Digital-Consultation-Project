<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'time' => $this->time,
            'is_booked' =>$this->is_booked
        ];
    }
}
