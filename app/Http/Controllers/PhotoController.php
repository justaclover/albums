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
        return PhotoResource::collection(Photo::where('album_id', $album->id)->get());
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album, Photo $photo)
    {
        return PhotoResource::make($photo);
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
                'image' => 'mimes:jpeg,jpg,png,gif,bmp,svg'
            ]
        );

        $photo = Photo::create(
            [
                'album_id' => $album->id,
                'title' => $request->title,
                'description' => $request->description,
                'image' => null,
            ]
        );

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();

        $path = 'albums\photos/';
        $filename = $photo->id . "_" . "image" . "." . $extension;
        $file->move(public_path($path), $filename);
        $photo->update(['image' => public_path($path).$filename]);

        return response()->json([
            "data" => [
                "album_id" => $photo->album_id,
                "title" => $photo->title,
                "description" => $photo->description,
            ]
        ], 201);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

        if ($request->has('image')) {
            File::delete($photo->image);

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            $path = 'albums\photos/';
            $filename = $photo->id . "_" . "image" . "." . $extension;
            $file->move(public_path($path), $filename);
            $photo->update(['image' => public_path($path).$filename]);
        }

        $photo->update(
            [
                'album_id' => $album->id,
                'title' => $request->title,
                'description' => $request->description
            ]
        );

        return response()->json([
            "data" => [
                "title" => $photo->title,
                "description" => $photo->description,
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
