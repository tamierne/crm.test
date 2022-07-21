<?php

namespace App\Http\Resources;

use App\Http\Resources\ProjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'VAT' => $this->VAT,
            'address' => $this->address,
            'projects' => $this->projects->isEmpty() ? 'No projects' : ProjectResource::collection($this->projects),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
