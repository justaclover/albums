<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('albums', AlbumController::class);
Route::resource('albums.photos', PhotoController::class);
