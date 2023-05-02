<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SendMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{

    protected $sendMailService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sendMailService = new SendMailService;
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $user = Auth::user();
        return view('app')->with('user', $user);
    }

    public function getUser()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    public function month1(Request $request){
         return response()->json($this->sendMailService->sendMonth1($request));
    }
    public function month3(Request $request){
        return response()->json($this->sendMailService->sendMonth3($request));
        return $this->sendMailService->sendMonth3($request);
    }
    public function month10(Request $request){
        return response()->json($this->sendMailService->sendMonth10($request));
    }
}
