<?php

use App\Http\Controllers\AlbumController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/add', [AlbumController::class, 'store']);
