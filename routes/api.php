<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\PhotoController;
use App\Http\Resources\PhotoResource;
use App\Models\Album;
use App\Models\City;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

//Cities
Route::resource('cities', CityController::class);
Route::get('city-restore/{city}', fn(int $city) => City::withTrashed()->where('id', $city)->first()->restore());
Route::delete('city-delete/{city}', fn(int $city) => City::withTrashed()->where('id', $city)->first()->forceDelete());


//Albums
Route::get('album-restore/{album}', fn(int $album) => Album::withTrashed()->where('id', $album)->first()->restore());
Route::delete('album-delete/{album}', fn(int $album) => Album::withTrashed()->where('id', $album)->first()->forceDelete());
Route::resource('cities.albums', AlbumController::class)->shallow();


//Photos
Route::resource('albums.photos', PhotoController::class);
Route::get('cities/{city}/photos', fn(City $city) => PhotoResource::collection($city->photos()->get()));
Route::get('photo-restore/{photo}', fn(int $photo) => Photo::withTrashed()->where('id', $photo)->first()->restore());
Route::delete('photo-delete/{photo}', fn(int $photo) => Photo::withTrashed()->where('id', $photo)->first()->forceDelete());


//Tags
Route::post('albums/{album}/add-tag', [AlbumController::class, 'addTag']);
Route::get('albums/{album}/get-tags', [AlbumController::class, 'getTags']);
Route::post('albums/{album}/remove-tags', [AlbumController::class, 'removeTags']);

Route::post('photos/{photo}/add-tag', [PhotoController::class, 'addTag']);
Route::get('photos/{photo}/get-tags', [PhotoController::class, 'getTags']);
Route::post('photos/{photo}/remove-tags', [PhotoController::class, 'removeTags']);
