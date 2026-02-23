<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'website' => $this->website,
            'logo_url' => $this->logo
                ? asset('storage/' . $this->logo)
                : null,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
