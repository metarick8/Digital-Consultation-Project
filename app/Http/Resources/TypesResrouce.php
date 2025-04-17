<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// For Expert_TypeResrouce:
class TypesResrouce extends JsonResource
{
    public function toArray($request)
    {
        return [
            'category_id' => (string) $this->id,
            'category' => $this->categories
        ];
    }
}
