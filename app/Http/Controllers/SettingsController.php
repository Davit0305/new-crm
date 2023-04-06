<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        try {
            $settings = Settings::all();
            return response()->json($settings, 200);
        } catch (\Exception $e) {
            throw $e;
            return response()->json('', 400);
        }

    }
        public
        function save(Request $request)
        {
            try {
                $settings = $request->data;
               foreach($settings as $key => $setting) {
                   Settings::where('id', $setting['id'])->update($setting);
               }
                return response()->json($settings, 200);
            } catch (\Exception $e) {
                throw $e;
                return response()->json('', 400);
            }

        }
}
