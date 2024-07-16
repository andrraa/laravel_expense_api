<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'type_id' => $this->type_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'category' => $this->category ? $this->category->name : null,
            'amount' => $this->amount,
            'date' => $this->date
        ];
    }
}
