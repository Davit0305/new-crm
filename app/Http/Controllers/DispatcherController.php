<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DispatcherController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $res = User::where('id',Auth::user()->id)->update(['online'=> date('Y-m-d H:i:s')]);
        $users = User::select('user.*', 'worker.*','user.id','worker.id as workerId')
            ->leftJoin('worker', 'worker.id', '=', 'user.worker_id');
        // фильтрация по полю поиска
        if (isset($_COOKIE['usersFilter_search'])) {
            $search = $_COOKIE['usersFilter_search'];
            $users->where('login', 'LIKE', "%" . $search . "%")
                ->orWhere('worker.name', 'LIKE', "%" . $search . "%")
                ->orWhere('worker.surname', 'LIKE', "%" . $search . "%");
        }else $users->where('enabled_dis',1);
        $users->orderBy('worker.surname', 'asc')->orderBy('user.enabled_dis', 'desc');
        $users = $users->get();
        return response()->json($users, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $user = $request->user;
        $userSave = [
            'r_dis'=>$user['r_dis'], 'r_den'=>$user['r_den'], 'r_nom'=>$user['r_nom'], 'r_hfo'=>$user['r_hfo'],
            'r_tmc'=>$user['r_tmc'], 'r_prz'=>$user['r_prz'],'r_toc'=>$user['r_toc'], 'r_shw'=>$user['r_shw'],
            'r_buh'=>$user['r_buh'], 'r_aut'=>$user['r_aut'], 'r_are'=>$user['r_are'], 'r_kur'=>$user['r_kur'],
            'r_sot'=>$user['r_sot'], 'r_otc'=>$user['r_otc'], 'r_buk'=>$user['r_buk'], 'r_adb'=>$user['r_adb'],
            'r_bar'=>$user['r_bar'] ? 1 : 0,'r_vak'=>$user['r_vak'],'r_nku'=>$user['r_nku'], 'admin'=>$user['admin'],
            'enabled_dis'=>$user['enabled_dis'],'login' => $user['login'],'worker_id' => $user['worker_id']
        ];
        if(!empty($request['pass'])) {
            $userSave['pass'] = md5($request['pass']);
        }
        $res = User::where('id', $user['id'])->update($userSave);
        return response()->json('Диспетчер Сохранен', 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newUser(Request $request){

        if (!empty($request->addUser)) {
            User::create(['login' => $request->addUser, 'enabled_dis' => 1]);
            return response()->json('Диспетчер Сохранен', 200);
        }
        return response()->json(' ', 400);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request){
       $user = User::find($request->id);
       if($user->worker_id != 0){
           Worker::find($user->worker_id)->delete();
       }
      $res = $user->delete();
        return response()->json('Диспетчер Удален', 200);
    }
}
