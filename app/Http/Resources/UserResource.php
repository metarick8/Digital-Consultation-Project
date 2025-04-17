<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'user_id' => (string) $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'budget' => $this->budget
        ];
    }
}
