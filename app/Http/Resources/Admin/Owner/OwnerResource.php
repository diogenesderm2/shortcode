<?php

namespace App\Http\Resources\Admin\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'property_name' => $this->property_name,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'cnpj' => $this->cnpj,
            'created_at' => $this->created_at,
        ];
    }
}
