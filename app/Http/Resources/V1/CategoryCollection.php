<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "success" => true,
            "action" => "Consulta categorias",
            'items' => $this->collection,
            'meta' => [
                'organization' => 'Smart Ranks',
                'authors' => 'Jorge Usuga'
            ],
            'type' => 'Categories'
        ];
    }
}
