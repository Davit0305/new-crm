<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\City;
use App\Models\Client;
use App\Models\ClientPhone;
use App\Models\ClientRate;
use App\Models\Driver;
use App\Models\DriverType;
use App\Models\Order;
use App\Models\OrderComment;
use App\Models\OrderPhoto;
use App\Models\OrderSource;
use App\Models\PaymentType;
use App\Models\Point;
use App\Models\TempFile;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class OrdersController extends Controller
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


    public function getOrders()
    {
        $orders = Order::getOrdersWithCookieFilter();
        return response()->json($orders);
    }

    public function getOrderOnce($id)
    {
        $order = Order::getOnceOrder($id);
        return response()->json($order);
    }

    public function addOrder()
    {
        $order = Order::createEmptyOrder();
        return response()->json($order);
    }

    public function saveOrder(Request $request)
    {
        $new_order = $request->get('order');
        $order = Order::find($new_order['id']);
        $order->name = $new_order['name'] ? $new_order['name'] : '';
        $order->phone = $new_order['phone'] ? $new_order['phone'] : '';
        $order->email = $new_order['email'] ? $new_order['email'] : '';
        $order->email = $new_order['email'] ? $new_order['email'] : '';
        $order->sms = $new_order['sms'] ? $new_order['sms'] : 0;
        $order->client_country = $new_order['client_country'] ? $new_order['client_country'] : 0;
        $order->client_gender = $new_order['client_gender'] ? $new_order['client_gender'] : 0;
        $order->client_status_vip = $new_order['client_status_vip'] ? $new_order['client_status_vip'] : 0;
        $order->client_status_corp = $new_order['client_status_corp'] ? $new_order['client_status_corp'] : 0;
        $order->client_status_partner = $new_order['client_status_partner'] ? $new_order['client_status_partner'] : 0;
        $order->polname = $new_order['polname'] ? $new_order['polname'] : '';
        $order->polphone = $new_order['polphone'] ? $new_order['polphone'] : '';
        $order->otkuda = $new_order['otkuda'] ? $new_order['otkuda'] : '';
        $order->adres = $new_order['adres'] ? $new_order['adres'] : '';
        $order->recipient_gender = $new_order['recipient_gender'] ? $new_order['recipient_gender'] : 0;
        $order->degree_id = $new_order['degree_id'] ? $new_order['degree_id'] : 1;
        $order->giftevent_id = $new_order['giftevent_id'] ? $new_order['giftevent_id'] : 1;
        $order->cvety = $new_order['cvety'] ? $new_order['cvety'] : 0;
        $order->tovar = $new_order['tovar'] ? $new_order['tovar'] : 0;
        $order->upakovka = $new_order['upakovka'] ? $new_order['upakovka'] : 0;
        $order->prochee = $new_order['prochee'] ? $new_order['prochee'] : 0;
        $order->nacenka = $new_order['nacenka'] ? $new_order['nacenka'] : 0;
        $order->dostavka = $new_order['dostavka'] ? $new_order['dostavka'] : 0;
        $order->sum_mon_2 = $new_order['sum_mon_2'] ? $new_order['sum_mon_2'] : 0;
        $order->sum_mon = $new_order['sum_mon'] ? $new_order['sum_mon'] : 0;
        $order->summa = $new_order['summa'] ? $new_order['summa'] : 0;
        $order->vip = $new_order['vip'] ? $new_order['vip'] : 1;
        $order->discount_card = $new_order['discount_card'] ? $new_order['discount_card'] : '';
        $order->money = $new_order['money'] ? $new_order['money'] : 0;
        $order->money_2 = $new_order['money_2'] ? $new_order['money_2'] : 0;
        $order->bank = $new_order['bank'] ? $new_order['bank'] : 0;
        $order->driver_id = $new_order['driver_id'] ? $new_order['driver_id'] : 0;
        $order->driver = $new_order['driver'] ? $new_order['driver'] : '';
        $order->seller_fio = $new_order['seller_fio'] ? $new_order['seller_fio'] : '';
        $order->tekstotkrytki = $new_order['tekstotkrytki'] ? trim($new_order['tekstotkrytki']) : '';
        $order->comment = $new_order['comment'] ? trim($new_order['comment']) : '';
        $order->tocka_id = $new_order['tocka_id'] ? $new_order['tocka_id'] : 0;
        $order->type_driver = $new_order['type_driver'] ? $new_order['type_driver'] : 0;
        $order->driver_summ = $new_order['driver_summ'] ? $new_order['driver_summ'] : 0;
        $order->show_in_app = $new_order['show_in_app'] ? $new_order['show_in_app'] : 0;
        $order->samo = $new_order['samo'] ? $new_order['samo'] : 0;
        $order->express = $new_order['express'] ? $new_order['express'] : 0;
        $order->soon = $new_order['soon'] ? $new_order['soon'] : 0;
        $order->driver_done = $new_order['driver_done'] ? $new_order['driver_done'] : 0;
        $order->cheizakaz = $new_order['cheizakaz'] ? $new_order['cheizakaz'] : 0;
        $order->type = $new_order['type'] ? $new_order['type'] : 0;
        $order->status = $new_order['status'] ? $new_order['status'] : 3;
        $order->istochnik_zakaza = $new_order['istochnik_zakaza'] ? $new_order['istochnik_zakaza'] : 1;
        $order->cancelreason_id = $new_order['cancelreason_id'] ? $new_order['cancelreason_id'] : 1;
        $order->brand_id = $new_order['brand_id'] ? $new_order['brand_id'] : 1;
        $order->internet = $new_order['internet'] ? $new_order['internet'] : '';
        $order->cancelreason_comment = $new_order['cancelreason_comment'] ? $new_order['cancelreason_comment'] : '';
        $order->data = $new_order['delivery_date'] ? date('d.m.Y', strtotime($new_order['delivery_date'])) : '';
        $order->time = $new_order['delivery_date'] ? date('H:i:s', strtotime($new_order['delivery_date'])) : '00:00:00';
        $order->modifed = Auth::id();
        $order->date = date('Y-m-d H:i:s');
        $order->save();

        if(!empty($order->phone)) {
            $client_phone = ClientPhone::where('phone', $order->phone)->first();
            if($client_phone) {
                $client = $client_phone->client;
            } else {
                $client = new Client();
            }

            $client->name = $order->name;
            $client->country = $order->client_country;
            $client->email = $order->email;
            $client->gender = $order->client_gender;
            $client->status_vip = $order->client_status_vip;
            $client->status_corp = $order->client_status_corp;
            $client->status_partner = $order->client_status_partner;
            $client->save();

            if(!$client_phone) {
                $client_phone = new ClientPhone();
                $client_phone->phone = $order->phone;
                $client_phone->active = 1;
                $client_phone->date_add = date('Y-m-d H:i:s');
                $client_phone->client_id = $client->id;
                $client_phone->save();
            }
        }

        if(isset($client) && $new_order['clientrate'] && ($new_order['clientrate']['comment'] || !empty($new_order['clientrate']['hashtags']))) {
            $clientrate = $order->clientrate;
            if($clientrate) {
                $clientrate->comment = $new_order['clientrate']['comment'] ? $new_order['clientrate']['comment'] : '';
                $clientrate->hashtags_id_serialized = $new_order['clientrate']['hashtags'] ? serialize($new_order['clientrate']['hashtags']) : serialize([]);
                $clientrate->save();
            } else {
                $clientrate = new ClientRate();
                $clientrate->client_id = $client->id;
                $clientrate->user_id = Auth::id();
                $clientrate->order_id = $order->id;
                $clientrate->comment = $new_order['clientrate']['comment'] ? $new_order['clientrate']['comment'] : '';
                $clientrate->hashtags_id_serialized = $new_order['clientrate']['hashtags'] ? serialize($new_order['clientrate']['hashtags']) : serialize([]);
                $clientrate->save();
            }
        }
        $new_order = Order::getChangedOrder($order->number);

        return response()->json($new_order);
    }

    public function getOrdersSearchData()
    {
        $data['payment_types'] = PaymentType::where('enabled', 1)->orderBy('sort')->get();
        $data['order_sources'] = OrderSource::where('enabled', 1)->orderBy('id')->get();
        $data['users'] = User::select('user.*')
            ->join('worker', 'worker.id', '=', 'user.worker_id')
            ->orderBy('worker.surname')
            ->where('enabled_dis', 1)
            ->get();
        $data['brands'] = Brand::all();
        $data['points'] = Point::where('enabled', 1)->with('brand')->orderBy('name')->get();
        $data['cities'] = City::orderBy('id')->get();
        $data['driver_types'] = DriverType::where('enabled', 1)->get();

        return response()->json($data);
    }

    public function storeImage(Request $request)
    {
        $request->validate([
            'image.*' => 'mimes:jpeg,png,jpg,gif,svg,bmp'
        ]);
        $file = $request->file('file');
        $order_number = $request->get('order_id');
        $type = $request->get('type');
        if ($file) {
            $imageName = uniqid('image_') . '.' . $file->getClientOriginalExtension();
            $folder = '/public/images/order_'.$type.'/';
            $file->storeAs($folder, $imageName);
//            TempFile::addTempForFiles($imageName, $folder, $order_number);
            $order_photo = new OrderPhoto();
            $order_photo->id_zak = $order_number;
            $order_photo->id_dis = Auth::id();
            $order_photo->date = date('Y-m-d H:i:s');
            $order_photo->type = $folder == '/public/images/order_before/' ? 1 : 2;
            $order_photo->file = $imageName;
            $order_photo->save();
            $image = OrderPhoto::find($order_photo->id);
            return response()->json(['status' => 'success', 'file' => $image]);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

//    public function getTemporaryFiles(Request $request)
//    {
//        $order_id = $request->get('order_id');
//        $type = $request->get('type');
//        $user_id = Auth::id();
//        $folder = '/public/images/order_'.$type.'/';
//        $files = TempFile::getTemporaryFiles($user_id, $order_id, $folder);
//        $ids = [];
//        foreach ($files as $file) {
//            $order_photo = new OrderPhoto();
//            $order_photo->id_zak = $order_id;
//            $order_photo->id_dis = $file->user_id;
//            $order_photo->date = date('Y-m-d H:i:s');
//            $order_photo->type = $file->folder == '/public/images/order_before/' ? 1 : 2;
//            $order_photo->file = $file->name;
//            $order_photo->save();
//            $file->delete();
//            $ids[] = $order_photo->id;
//        }
//        $new_files = OrderPhoto::find($ids);
//        if (!$files->isEmpty()) {
//            return response()->json(['status' => 'success', 'files' => $new_files]);
//        } else {
//            return response()->json(['status' => 'empty']);
//        }
//    }


    public function removeImage(Request $request)
    {
        $image_id = $request->get('image_id');
        $order_photo = OrderPhoto::find($image_id);
        $path = $order_photo->type === 1 ? 'public/images/order_before/'.$order_photo->file : 'public/images/order_after/'.$order_photo->file;
        if(Storage::exists($path)){
            Storage::delete($path);
        }
        $order_photo->delete();
        return response()->json(['status' => 'success']);
    }


    public function updateComment(Request $request)
    {
        $id = $request->get('id');
        $text = $request->get('text');
        $comment = OrderComment::find($id);
        $comment->text = $text;
        $comment->date_modified = date('Y-m-d H:i:s');
        $comment->save();
//        $date = date('H:i d.m.Y', strtotime($comment->date_modified));

        return response()->json($comment);
    }

    public function addNewComment(Request $request)
    {
        $text = $request->get('text');
        $order_id = $request->get('order_id');
        $comment = new OrderComment();
        $comment->text = $text;
        $comment->user_id = Auth::id();
        $comment->order_id = $order_id;
        $comment->com_type = 0;
        $comment->date_add = date('Y-m-d H:i:s');
        $comment->date_modified = date('Y-m-d H:i:s');
        $comment->save();
        $comment->load('user');

        return response()->json($comment);
    }


    public function searchDrivers(Request $request)
    {
        $search = $request->get('search');
        $drivers = Driver::getDriversFilter($search);
        return response()->json($drivers);
    }

    public function searchSellers(Request $request)
    {
        $search = $request->get('search');
        $sellers = Worker::getSellerSearch($search);
        return response()->json($sellers);
    }

    public function searchPoints(Request $request)
    {
        $search = $request->get('search');
        $points = Point::getPointSearch($search);
        return response()->json($points);
    }
}
