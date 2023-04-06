<?php

namespace App\Http\Controllers;

use App\Models\Arhive;
use App\Models\Orders;
use App\Models\TempOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminOrders extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        try {
            // Begin a transaction
            DB::beginTransaction();

            $ids = [];
            $orders = Orders::whereNotNull('data')
                ->where('data', '!=', '')
                ->whereDate('date', '<=', $request->all()['date'])
                ->limit(100)
                ->get();
            foreach ($orders as $k => $val) {
                $ids[] = $val->id;
            }
            TempOrders::truncate();
            TempOrders::insert($orders->toArray());
            $arhve = TempOrders::select("id", "number", "name", "inkognito", "samo", "phone"
                , "email", "email_true", "email_active", "polname", "polphone"
                , "foto", "data", "time", "soon" , "status", "akciya"
                , "rasp", "dis_ras"  , "time_ras" , "driver", "driver_id", "id_smsintel" , "type_driver" ,
                "driver_summ", "sms_drv", "send_phone", "sms_drv_true", "sms" , "sendtrue"
                , "sendaktive" , "modifed", "comment" , "internet", "otkuda" , "type",
                "money", "sum_mon" , "money_2", "sum_mon_2" , "vip" , "sale"
                , "discount" , "pin" , "summa_bonus", "bonus_true"  , "bank"
                , "adres", "cheizakaz", "dobavil", "cvety", "prochee"
                , "dostavka", "nacenka", "upakovka" , "tovar"
                , "summa", "tocka_id", "istochnik_zakaza", "sotrudnik"
                , "date", "dateadd", "driver_closed", "domofon")->get()->toArray();

            Arhive::whereIn('id', $ids)->delete();
            $res = Arhive::insert($arhve);
            Orders::whereIn('id', $ids)->delete();
            DB::commit();
            return response()->json('', 200);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return response()->json('', 400);
        }


    }
}
