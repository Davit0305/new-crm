<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities()
    {
        $city = City::get();
        return response()->json($city, 200);
    }
}
