<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\File;

/**
 * @property int $id
 * @property int album_id
 * @property string title
 * @property string description
 * @property File image
 */

class Photo extends Model
{
    use HasFactory;
    protected $fillable = [
        "album_id",
        "title",
        "description",
        "image"
    ];
}
