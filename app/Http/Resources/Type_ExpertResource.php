<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Type_ExpertResource extends JsonResource
{
    public function toArray($request)
    {
        foreach($this->experts as $expert)
        $all_experts[] = new ExpertsResource($expert);
        return [
            'category_id' => (string) $this->id,
            'category' => $this->categories,
            'experts' => $all_experts
        ];
    }
}
