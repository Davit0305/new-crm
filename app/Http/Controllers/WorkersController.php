<?php

namespace App\Http\Controllers;

use App\Exports\WorkersExport;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Worker;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WorkersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function saveWorker(Request $request)
    {
        $id = $request->get('id');
        $phone = $request->get('phone');
        $worker = Worker::find($id);
        $worker->phone = $phone;
        $worker->save();

        return response()->json($worker);
    }

    public function getDrivers()
    {
        return response()->json(['workers' => 13112], 200);
    }

    public function getWorkers()
    {
        $workers = Worker::getWorkersWithCookieFilter();
        return response()->json($workers);
    }

    public function getWorkersSearchData()
    {
        $data['jobs'] = Appointment::where('enabled', 1)->orderBy('name')->get();
        $data['deps'] = Department::where('enabled', 1)->orderBy('name')->get();

        return response()->json($data);
    }

    public function exportWorkers()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        return Excel::download(new WorkersExport, 'workers.xlsx');
    }
}
