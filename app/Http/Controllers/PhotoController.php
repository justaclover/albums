<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlbumResource;
use App\Http\Resources\PhotoResource;
use App\Models\Album;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Album $album)
    {
        return PhotoResource::collection($album->photos());
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album, Photo $photo)
    {
        return PhotoResource::make($album->photos()->find($photo->id));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Album $album)
    {
        $request->validate(
            [
                'title' => 'required|max:255|unique:photos,title',
                'description' => 'nullable|max:500',
                'image' => 'required|mimes:jpeg,jpg,png,gif,bmp,svg'
            ]
        );

        $photo = $album->photos()->updateOrCreate([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->has('image')) {
            $photo->addMediaFromRequest('image')->toMediaCollection("photos");
        }

        return response()->json([
            "data" => [
                "album_id" => $photo->album_id,
                "title" => $photo->title,
                "description" => $photo->description,
                "image" => $photo->getMedia('photos')->last()->getUrl()
            ]
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album, Photo $photo)
    {
        $request->validate(
            [
                'title' => 'required|max:255',
                'description' => 'nullable|max:500',
                'image' => 'mimes:jpeg,jpg,png,gif,bmp,svg'
            ]
        );


        $photo->update(
            [
                'album_id' => $album->id,
                'title' => $request->title,
                'description' => $request->description
            ]
        );

        if ($request->has('image')) {
            $photo->getMedia('photos')->last()->delete();
            $photo->addMediaFromRequest('image')->toMediaCollection("photos");
        }

        return response()->json([
            "data" => [
                "album_id" => $photo->album_id,
                "title" => $photo->title,
                "description" => $photo->description,
                "image" => $photo->getMedia('photos')->last()->getUrl()
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album, Photo $photo)
    {
        $photo->delete();
        return response()->noContent();
    }
}
