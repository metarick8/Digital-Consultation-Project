<?php

namespace App\Http\Resources;
use App\Traits\HttpResponses;
use Illuminate\Http\Resources\Json\JsonResource;

// For Type_ExpertResource:
class ExpertsResource extends JsonResource
{
    use HttpResponses;

    public function toArray($request)
    {
        


        return [
            
            'expert_id' => (string) $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image_path' => $this->image_path,
            'experience' => $this->experience,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'budget' => $this->budget,
            'price' => $this->price,
            'rate' => $this->rate
            ];
    }
}
