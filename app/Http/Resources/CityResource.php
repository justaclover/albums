<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin City
 */

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        self::$wrap = false;
        return [
            "city" => [
                "id" => $this->id,
                "title" => $this->title,
                "thumbnailImg" => $this->getMedia('cities')[0]->getUrl(),
            ]
        ];
    }
}
