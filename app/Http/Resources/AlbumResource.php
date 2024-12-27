<?php

namespace App\Http\Resources;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Album
 */

class AlbumResource extends JsonResource
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
            "album" => [
                "id" => $this->id,
                "title" => $this->title,
                "description" => $this->description,
                "thumbnailImg" => $this->getMedia('albums')[0]->getUrl(),
            ]
        ];
    }
}
