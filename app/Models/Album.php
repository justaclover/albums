<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\File;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

/**
 * @property int $id
 * @property int city_id
 * @property string title
 * @property string description
 */
class Album extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;
    use HasTags;
    protected $fillable = [
        "city_id",
        "title",
        "description"
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
