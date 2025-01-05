<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\File;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


/**
 * @property int $id
 * @property string title
 */
class City extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;
    protected $fillable = [
        "title",
    ];

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    public function photos(): HasManyThrough
    {
        return $this->hasManyThrough(Photo::class, Album::class);
    }
}
