<?php

namespace App\Http\Controllers;

use App\Models\CourierPrices;
use Illuminate\Http\Request;

class CouriersController extends Controller
{
    public function index(){
        try {
            $courierPrices = CourierPrices::all();
            return response()->json($courierPrices, 200);
        } catch (\Exception $e) {
            throw $e;
            return response()->json('', 400);
        }
    }

    public
    function save(Request $request)
    {
        try {

            $items = $request->items;
            $items[0]['min_distance'] = $request->min_distance;
            $items[0]['price'] = $request->min_price;
            $items[1]['max_distance'] = $request->max_distance;
            $items[1]['price'] = $request->max_price;
            $items[2]['price'] = intval($request->once_km);
            foreach ($items as $item) {
                if($item['type'] != 'once_km')
                CourierPrices::updateOrCreate($item);
            }
            CourierPrices::where('id',3)->update(['price' => $request->once_km]);
            return response()->json('', 200);
        } catch (\Exception $e) {
            throw $e;
            return response()->json('', 400);
        }

    }
}
