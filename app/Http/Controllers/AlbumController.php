<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function store(AlbumRequest $request)
        {
            dd("hello world");
            //$album = Album::query()->create($request->validated());
            //$user->lunarMissions()->create($request->validated()['mission']);

//            return response()->json([
//                "data" => [
//                    "title" => $album->title,
//                    "description" => $album->description,
//                ]
//            ], 201);
        }
}
