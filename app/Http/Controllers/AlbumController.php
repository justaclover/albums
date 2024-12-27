<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use App\Models\City;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AlbumController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(Album::class, 'album');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(City $city)
    {
        return AlbumResource::collection($city->albums());
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
    public function store(Request $request, City $city)
    {
        $request->validate(
            [
                'title' => 'required|max:255',
                'description' => 'nullable|max:500',
                'thumbnailImg' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,svg'
            ]
        );

        $album = $city->albums()->updateOrCreate(
            [
                'title' => $request->title,
                'description' => $request->description,
            ]
        );

        if ($request->has('thumbnailImg')) {
            $album->addMediaFromRequest('thumbnailImg')->toMediaCollection("albums");
        }

        return response()->json([
            "data" => [
                "city_id" => $album->city_id,
                "title" => $album->title,
                "description" => $album->description,
                "thumbnailImg" => $album->getMedia('albums')->last()->getUrl()
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

        $album->update(
            [
                'title' => $request->title,
                'description' => $request->description,
            ]
        );

        if ($request->has('thumbnailImg')) {
            $album->getMedia('albums')->first()->delete();
            $album->addMediaFromRequest('thumbnailImg')->toMediaCollection("albums");
        }

        return response()->json([
            "data" => [
                "city_id" => $album->city_id,
                "title" => $album->title,
                "description" => $album->description,
                "thumbnailImg" => $album->getMedia('albums')->first()->getUrl()
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        $album->delete();
        return response()->noContent();
    }
}
