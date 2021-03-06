<?php

namespace Lfgscavelli\Todolist\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VocabulariesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'categories' => CategoriesResource::collection($this->categories),
        ];
    }
}
