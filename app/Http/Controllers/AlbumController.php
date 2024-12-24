<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AlbumController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AlbumResource::collection(Album::query()->get());
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        return AlbumResource::make($album);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate(
            [
                'title' => 'required|max:255',
                'description' => 'nullable|max:500',
                'thumbnailImg' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,svg'
            ]
        );

        $album = Album::create(
            [
                'title' => $request->title,
                'description' => $request->description,
                'thumbnailImg' => null,
            ]
        );

        if ($request->has('thumbnailImg')) {
            $file = $request->file('thumbnailImg');
            $extension = $file->getClientOriginalExtension();

            $path = 'albums\thumbnails/';
            $filename = $album->id . "_" . "thumbnail" . "." . $extension;
            $file->move(public_path($path), $filename);

            $album->update(['thumbnailImg' => public_path($path) . $filename]);
        }

        return response()->json([
            "data" => [
                "title" => $album->title,
                "description" => $album->description,
            ]
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        $request->validate(
            [
                'title' => 'required|max:255',
                'description' => 'nullable|max:500',
                'thumbnailImg' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,svg'
            ]
        );

        if ($request->has('thumbnailImg')) {
            if ($album->thumbnailImg != null) {
                File::delete($album->thumbnailImg);
            }

            $file = $request->file('thumbnailImg');
            $extension = $file->getClientOriginalExtension();

            $path = 'albums\thumbnails/';

            $filename = $album->id . "_" . "thumbnail" . "." . $extension;
            $file->move(public_path($path), $filename);
            $album->update(['thumbnailImg' => public_path($path) . $filename]);
        }

        $album->update(
            [
                'title' => $request->title,
                'description' => $request->description,
            ]
        );

        return response()->json([
            "data" => [
                "title" => $album->title,
                "description" => $album->description,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        $this->authorize('delete', $album);
    }
}
