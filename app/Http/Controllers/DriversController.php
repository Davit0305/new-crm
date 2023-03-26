<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriversController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDrivers(Request $request): \Illuminate\Http\JsonResponse
    {

        $drivers = Driver::join('worker', 'worker.id', 'drivers.worker_id');
        $drivers->select('*', 'drivers.id as id', 'drivers.phone as phone',
            'drivers.enabled as enabled', 'worker.middlename as name');
        // фильтрация по полю поиска
        if (isset($_COOKIE['driverFilter_search'])) {
            $search = $_COOKIE['driverFilter_search'];
            $drivers->where('worker.name', 'LIKE', "%" . $search . "%")
                ->orWhere('worker.surname', 'LIKE', "%" . $search . "%")
                ->orWhere('worker.middlename', 'LIKE', "%" . $search . "%")
                ->orWhere('drivers.phone', 'LIKE', "%" . $search . "%");
        }

        if (isset($_COOKIE['driverFilter_shtat'])) {
            $shtat = $_COOKIE['driverFilter_shtat'];
            $drivers->where('drivers.shtat',$shtat );
        }

        $drivers = $drivers->orderBy('surname')->get();
        return response()->json($drivers, 200);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveDrivers(Request $request)
    {
        $driver = $request->driver;
        $res = Driver::where('id', $driver['id'])->update([
            'avto' => $driver['avto'],
            'nomer' => $driver['nomer'],
            'name' => $driver['name'],
            'phone' => $driver['phone'],
            'shtat' => $driver['shtat'],
            'city_id' => $driver['city_id'],
            'enabled' => intval($driver['enabled']),
            'allow_send_sms' => $driver['allow_send_sms'] ? 1 : 0
        ]);
        return response()->json('Курьер Сохранен', 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        Driver::find($request->id)->delete();
        return response()->json('Курьер Удален', 200);
    }
}
