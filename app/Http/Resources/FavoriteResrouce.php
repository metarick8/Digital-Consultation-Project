<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResrouce extends JsonResource
{
    public function toArray($request)
    {
        /* Here, we check about if the array of experts is empty or not!
        Because we can't pass the null value into the array ($all_experts) */
        if (sizeof($this->experts) != null)
        {
            foreach ($this->experts as $expert)
                $all_experts[] = new ExpertsResource($expert);
            return [
                'user_id' => (string) $this->id,
                'Expert' => $all_experts
            ];
        }
        else
            return "The list of favorite experts is empty!";
    }
}
