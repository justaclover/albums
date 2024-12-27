<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlbumResource;
use App\Http\Resources\CityResource;
use App\Models\Album;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CityResource::collection(City::query()->get());
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        return CityResource::make($city);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|max:255',
                'thumbnailImg' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,svg'
            ]
        );

        $city = City::create(['title' => $request->title]);

        if ($request->has('thumbnailImg')) {
            $city->addMediaFromRequest('thumbnailImg')->toMediaCollection("cities");
        }

        return response()->json([
            "data" => [
                "title" => $city->title,
                "thumbnailImg" => $city->getMedia('cities')[0]->getUrl()
            ]
        ], 201);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        $request->validate(
            [
                'title' => 'required|max:255',
                'thumbnailImg' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,svg'
            ]
        );

        $city->update(['title' => $request->title]);

        if ($request->has('thumbnailImg')) {
            $city->getMedia('cities')->first()->delete();
            $city->addMediaFromRequest('thumbnailImg')->toMediaCollection("cities");
        }
        return response()->json([
            "data" => [
                "title" => $city->title,
                "thumbnailImg" => $city->getMedia('cities')->first()->getUrl()
            ]
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        $city->delete();
        return response()->noContent();
    }
}
