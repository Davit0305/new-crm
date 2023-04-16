<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\MessageNotiSettings;
use App\Services\SendPushService;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    protected $sendPushService;

    public function __construct()
    {
        $this->sendPushService = new SendPushService;
    }

    public function index(Request $request)
    {
        try {
            $messageNotiSettings = MessageNotiSettings::all();
            return response()->json($messageNotiSettings, 200);
        } catch (\Exception $e) {
            throw $e;
            return response()->json('', 400);
        }

    }

    public
    function save(Request $request)
    {
        try {
            $messageNotiSettings = $request->all();
            foreach ($messageNotiSettings as $key => $value) {
                foreach ($value as $val) {
                    MessageNotiSettings::where('id', $val['id'])->update(['noti_type' => $val['noti_type']]);
                }
            }
            return response()->json($messageNotiSettings, 200);
        } catch (\Exception $e) {
            throw $e;
            return response()->json('', 400);
        }

    }

    public
    function sendPush(Request $request)
    {
        try {
            $arData =array(
                'body' => $request->push_desc,
                'title' => $request->push_title
            );
            $token = $request->user["push_token"];
            $this->sendPushService->sendPush($token, $arData);
            return response()->json('', 200);
        } catch (\Exception $e) {
            throw $e;
            return response()->json('', 400);
        }

    }
    public function getUserWithPushToken(){
        try {
            $users = Driver::select('drivers.id as id', 'worker.name as name', 'worker.surname as surname',
                'worker.middlename as middlename', 'drivers.push_token as push_token')
                ->whereNotNull('push_token')
                ->join('worker', 'worker.id', '=', 'drivers.worker_id')
                ->get();
            return response()->json($users, 200);
        } catch (\Exception $e) {
            throw $e;
            return response()->json('', 400);
        }
    }
}
