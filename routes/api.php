<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PhotoController;
use App\Models\Album;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('album', AlbumController::class);
Route::resource('album.photos', PhotoController::class);

Route::get('/album/{album}/photos', fn(Album $album) => $album->photos);
Route::get('/album/{album}/photos', fn(Album $album) => Photo::where('album_id', $album->id)->get());
Route::get('userDelete', fn() => User::findOrFail(1)->delete());

Route::get('users', fn() => User::withTrashed()->get());
Route::get('users/{user}', fn(int $user) => User::withTrashed()->where('id', $user)->first()->forceDelete());
