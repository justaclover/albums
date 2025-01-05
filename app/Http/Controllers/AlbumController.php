<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Http\Resources\AlbumResource;
use App\Http\Resources\PhotoResource;
use App\Models\Album;
use App\Models\City;
use App\Models\Photo;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Container\Attributes\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
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
    public function index(City $city): JsonResource
    {
        return AlbumResource::collection($city->albums());
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album): JsonResource
    {
        return AlbumResource::make($album);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, City $city): JsonResponse
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
    public function update(Request $request, Album $album): JsonResponse
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
    public function destroy(Album $album): Response
    {
        $album->delete();
        return response()->noContent();
    }

    public function addTag(Request $request, Album $album): JsonResponse
    {
        $request->validate(['title' => 'required|max:255']);

        $album->attachTag($request->title);
        $album->photos()->each(function ($photo, $key) {
            $photo->attachTag($photo->album->tags()->get()->last());
        });
        return response()->json([
            "tags" => $album->tags()->get()->last()
        ]);

    }

    public function getTags(Album $album): JsonResponse
    {
        return response()->json([
            "tags" => $album->tags()->get()
        ]);
    }

    public function removeTags(Request $request, Album $album): JsonResponse
    {
        $album->detachTags($request->tags);
        foreach (PhotoResource::collection(Photo::where("album_id", $album->id)->get()) as $photo) {
            $photo->detachTags($request->tags);
        };

        return response()->json([
            "tags" => $album->tags()->get()
        ]);
    }
}
