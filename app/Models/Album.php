<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\File;

/**
 * @property int $id
 * @property string title
 * @property string description
 * @property File thumbnailImg
 */
class Album extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "description",
        "thumbnailImg"
    ];
}
