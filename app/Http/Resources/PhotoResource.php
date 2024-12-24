<?php

namespace App\Http\Resources;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Photo
 */

class PhotoResource extends JsonResource
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
            "photo" => [
                "id" => $this->id,
                "album_id" => $this->album_id,
                "title" => $this->title,
                "description" => $this->description,
                "image" => $this->image,
            ]
        ];
    }
}
