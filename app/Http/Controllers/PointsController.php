<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Raiony;
use App\Models\Tochka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request){

        $points = Tochka::with('modifiedUser','raiony');
        // фильтрация по полю поиска
        if (isset($_COOKIE['pointsFilter_search'])) {
            $search = $_COOKIE['pointsFilter_search'];
            $points->where('name', 'LIKE', "%" . $search . "%");
        }
        $points = $points->get();
         return response()->json($points, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request){
        $point = $request->point;
        $point['bonus'] = $point['bonus'] ? 1 : 0 ;
        unset($point['modified_user']);
        unset($point['raiony']);
        unset($point['i']);
        $res = Tochka::where('id', $point['id'])->update($point);
        return response()->json('Торговое точка Сохранен', 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request){
        Tochka::find($request->id)->delete();
        return response()->json('Торговое точка Удален', 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function raiony(){
        $raiony = Raiony::all();
        return response()->json($raiony, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveNewRaion(Request $request){
        $newRaionName = $request->newRaionName;
        $newRaionId = $request->newRaionId;
       $res = Raiony::create(['city_id' => $newRaionId,'name' => $newRaionName]);
        return response()->json($res, 200);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveNewCity(Request $request){
        $newCity = $request->newCity;
        $res = City::updateOrCreate(['name' => $newCity]);
        return response()->json($res, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNewPoint(Request $request){
        $addPoint = $request->addPoint;
        $res = Tochka::updateOrCreate(['name' => $addPoint,'modifed' => Auth::user()->id]);
        return response()->json($res, 200);
    }
}
