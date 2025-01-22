<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortUrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'short_url' => $this->short_url,
            'original_url' => $this->original_url,
            'domain' => $this->domain,
            'alias' => $this->alias,
            'uses' => $this->uses,
            'time_ago' => $this->time_ago,

            // Add the timezone to the formatted created_at attribute
            'created_at' => $this->created_at->format('Y-m-d H:i:s') . ' (' . $this->created_at->timezoneName . ')'
        ];
    }
}
