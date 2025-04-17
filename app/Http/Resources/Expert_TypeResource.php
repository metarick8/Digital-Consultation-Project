<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Expert_TypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (sizeof($this->types) != null)
        {
        foreach($this->types as $type)
            $all_types[] = new TypesResrouce($type);
        }
        else
        $all_types = "empty";
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
            'rate' => $this->rate,
            'types' => $all_types
        ];

    }
}
