<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class Order
 * @property int $id
 * @property string $number
 * @property string $name
 * @property int $inkognito
 * @property int $samo
 * @property string $phone
 * @property string $email
 * @property int $email_true
 * @property int $email_active
 * @property string $polname
 * @property string $polphone
 * @property int $foto
 * @property string $data
 * @property string $time
 * @property int $soon
 * @property int $express
 * @property int $status
 * @property int $akciya
 * @property string $rasp
 * @property int $dis_ras
 * @property string $time_ras
 * @property string $driver
 * @property int $driver_id
 * @property int $id_smsintel
 * @property int $type_driver
 * @property int $driver_summ
 * @property int $sms_drv
 * @property string $send_phone
 * @property int $sms_drv_true
 * @property int $sms
 * @property int $sendtrue
 * @property int $sendaktive
 * @property string $modifed
 * @property int $modified_api
 * @property string $comment
 * @property string $internet
 * @property string $otkuda
 * @property int $time_zone
 * @property int $type
 * @property int $money
 * @property int $sum_mon
 * @property int $money_2
 * @property int $sum_mon_2
 * @property int $vip
 * @property int $sale
 * @property string $discount
 * @property string $pin
 * @property int $summa_bonus
 * @property int $bonus_true
 * @property int $bank
 * @property string $adres
 * @property int $cheizakaz
 * @property int $dobavil
 * @property int $dobavil_api
 * @property int $cvety
 * @property int $prochee
 * @property int $dostavka
 * @property int $nacenka
 * @property int $upakovka
 * @property int $tovar
 * @property int $summa
 * @property int $tocka_id
 * @property int $tocka_city_id
 * @property int $istochnik_zakaza
 * @property int $sotrudnik
 * @property string $date
 * @property string $dateadd
 * @property int $driver_closed
 * @property string $domofon
 * @property string $seller_fio
 * @property int $show_in_app
 * @property int $driver_done
 * @property int $multi
 * @property int $brand_id
 * @property int $client_id
 * @property int $client_status_vip
 * @property int $client_status_corp
 * @property int $client_status_partner
 * @property int $client_gender
 * @property int $client_country
 * @property int $giftevent_id
 * @property int $degree_id
 * @property int $recipient_gender
 * @property int $cancelreason_id
 * @property string $cancelreason_comment
 * @property string $tekstotkrytki
 * @property int $oneclick
 * @property string $discount_card
 * @property int $discount_type
 * @property string $receipt
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Order extends Model
{
    protected $table = 'orders';

    public $timestamps = false;

    protected $fillable = [
        'number',
        'name',
        'inkognito',
        'samo',
        'phone',
        'email',
        'email_true',
        'email_active',
        'polname',
        'polphone',
        'foto',
        'data',
        'time',
        'soon',
        'express',
        'status',
        'akciya',
        'rasp',
        'dis_ras',
        'time_ras',
        'driver',
        'driver_id',
        'id_smsintel',
        'type_driver',
        'driver_summ',
        'sms_drv',
        'send_phone',
        'sms_drv_true',
        'sms',
        'sendtrue',
        'sendaktive',
        'modifed',
        'modified_api',
        'comment',
        'internet',
        'otkuda',
        'time_zone',
        'type',
        'money',
        'sum_mon',
        'money_2',
        'sum_mon_2',
        'vip',
        'sale',
        'discount',
        'pin',
        'summa_bonus',
        'bonus_true',
        'bank',
        'adres',
        'cheizakaz',
        'dobavil',
        'dobavil_api',
        'cvety',
        'prochee',
        'dostavka',
        'nacenka',
        'upakovka',
        'tovar',
        'summa',
        'tocka_id',
        'tocka_city_id',
        'istochnik_zakaza',
        'sotrudnik',
        'date',
        'dateadd',
        'driver_closed',
        'domofon',
        'seller_fio',
        'show_in_app',
        'driver_done',
        'multi',
        'brand_id',
        'client_id',
        'client_status_vip',
        'client_status_corp',
        'client_status_partner',
        'client_gender',
        'client_country',
        'giftevent_id',
        'degree_id',
        'recipient_gender',
        'cancelreason_id',
        'cancelreason_comment',
        'tekstotkrytki',
        'oneclick',
        'discount_card',
        'discount_type',
        'receipt'
    ];

    protected $appends = ['time_zero', 'date_adding', 'date_edit', 'time_with_zone', 'delivery_date'];

    public function userAdd()
    {
        return $this->hasOne('App\Models\User', 'id', 'dobavil');
    }

    public function userEdit()
    {
        return $this->hasOne('App\Models\User', 'id', 'modifed');
    }

    public function courier()
    {
        return $this->hasOne('App\Models\Driver', 'id', 'driver_id');
    }

    public function point()
    {
        return $this->hasOne('App\Models\Point', 'id', 'tocka_id');
    }

    public function city()
    {
        return $this->hasOne('App\Models\City', 'id', 'city_id');
    }

    public function brand()
    {
        return $this->hasOne('App\Models\Brand', 'id', 'brand_id');
    }

    public function orderType()
    {
        return $this->hasOne('App\Models\OrderType', 'id', 'type');
    }

    public function clientrate()
    {
        return $this->hasOne('App\Models\ClientRate', 'order_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\OrderComment', 'order_id', 'id')->orderByDesc('date_add');
    }

    public function photos()
    {
        return $this->hasMany('App\Models\OrderPhoto', 'id_zak', 'number');
    }

    public function getTimeZeroAttribute()
    {
        $time = '';
        if(!empty($this->time) && $this->time != '00:00:00') {
            $time = substr($this->time,0,5);
        }
        return $time;
    }

    public function getDateAddingAttribute()
    {
        $this->userAdd;
        return date("H:i d.m.Y", strtotime($this->dateadd));
    }

    public function getDateEditAttribute()
    {
        $this->userEdit;
        return date("H:i d.m.Y", strtotime($this->date));
    }

    public function getTimeWithZoneAttribute()
    {
        $time_with_zone = date("H:i", strtotime('+'.$this->time_zone.' hours', strtotime($this->time)));
        return $time_with_zone;
    }

    public function getDeliveryDateAttribute()
    {
        $delivery_date = $this->data != '' ? date("d.m.Y H:i", strtotime($this->data.' '.$this->time)) : '';
        return $delivery_date;
    }

    public static function getOnceOrderWithJoin()
    {
        $orders = self::select(
            'orders.*',
            DB::raw('COUNT(DISTINCT(foto.id)) as photo_count'),
            DB::raw('COUNT(DISTINCT(sms.id)) as sms_count'),
            DB::raw('COUNT(DISTINCT(order_comments.id)) as comments_count'),
            'tochka.name as tochka_name',
            'tochka.phone as tochka_phone',
            'tochka.sms_adres as tochka_adres',
            'sposoby_oplaty.name as payment_name',
            'drivers.phone as driver_phone',
            'drivers.nomer as driver_nomer',
            'drivers.avto as driver_avto',
            'type_driver.name as type_driver_name',
            'brand.short_name as brand_short_name',
            'order_types.short_name as type_short_name',
            'order_types.name as type_name',
            'order_types.short_name as type_short_name'
        )
            ->leftJoin('cities', 'cities.id', '=', 'orders.tocka_city_id')
            ->leftJoin('sposoby_oplaty', 'sposoby_oplaty.number', '=', 'orders.money')
            ->leftJoin('foto', 'orders.number', '=', 'foto.id_zak')
            ->leftJoin('sms_order', 'orders.id', '=', 'sms_order.order_id')
            ->leftJoin('order_comments', 'orders.id', '=', 'order_comments.order_id')
            ->leftJoin('sms', 'sms.id', '=', 'sms_order.sms_id')
            ->leftJoin('drivers', 'drivers.id', '=', 'orders.driver_id')
            ->leftJoin('type_driver', 'type_driver.id', '=', 'drivers.shtat')
            ->leftJoin('tochka', 'tochka.id', '=', 'orders.tocka_id')
            ->leftJoin('order_types', 'order_types.id', '=', 'orders.type')
            ->leftJoin('brand', 'brand.id', '=', 'orders.brand_id');

        return $orders;
    }

    public static function getOrdersWithCookieFilter()
    {
        $orders = self::getOnceOrderWithJoin();

        // фильтрация по способу оплаты
        if ( isset($_COOKIE['orderFilter_payment_type'])) {
            $payment_type = $_COOKIE['orderFilter_payment_type'];
            $orders->where('money', $payment_type);
        }

        // фильтрация по тому, как клиент узнал о компании
        if ( isset($_COOKIE['orderFilter_order_source'])) {
            $order_source = $_COOKIE['orderFilter_order_source'];
            $orders->where('istochnik_zakaza', $order_source);
        }

        // фильтрация по городу торговой точки
        if ( isset($_COOKIE['orderFilter_city'])) {
            $city = $_COOKIE['orderFilter_city'];
            $orders->where('tocka_city_id', $city);
        }

        // фильтрация по бренду торговой точки
        if ( isset($_COOKIE['orderFilter_brand'])) {
            $brand = $_COOKIE['orderFilter_brand'];
            $orders->where('brand_id', $brand);
        }

        // фильтрация по оператору, закрывшего заказ
        if ( isset($_COOKIE['orderFilter_close_disp'])) {
            $close_disp = $_COOKIE['orderFilter_close_disp'];
            $orders->where('modifed', $close_disp);
        }

        // фильтрация по оператору, добавившего заказ
        if ( isset($_COOKIE['orderFilter_add_disp'])) {
            $add_disp = $_COOKIE['orderFilter_add_disp'];
            $orders->where('dobavil', $add_disp);
        }

        // фильтрация по торговой точке
        if ( isset($_COOKIE['orderFilter_point'])) {
            $point = $_COOKIE['orderFilter_point'];
            $orders->where('tocka_id', $point);
        }

        // фильтрация по статусу заказа
        if ( isset($_COOKIE['orderFilter_status'])) {
            $status = $_COOKIE['orderFilter_status'];
            if($status > 0) {
                $orders->where('status', $status);
            }
        } else {
            $orders->where('status', 3);
        }

        // фильтрация по штатности курьера
        if ( isset($_COOKIE['orderFilter_driver_type'])) {
            $driver_type = $_COOKIE['orderFilter_driver_type'];
            $orders->where('type_driver', $driver_type);
        }

        // фильтрация по принятию заказа офисом или торговой точкой
        if ( isset($_COOKIE['orderFilter_from_office'])) {
            $from_office = json_decode($_COOKIE['orderFilter_from_office']);
            $orders->whereIn('cheizakaz', $from_office);
        }

        // фильтрация по дате доставки начало
        if ( isset($_COOKIE['orderFilter_date_start'])) {
            $date_start = $_COOKIE['orderFilter_date_start'];
            $orders->where(function ($q) use($date_start) {
                $date = date('d.m.Y', strtotime($date_start));
                $q->orWhere(DB::raw("STR_TO_DATE(data,'%d.%m.%Y')"), '>', DB::raw("STR_TO_DATE('".$date."','%d.%m.%Y')"));
                $q->orWhere(function ($q) use($date_start) {
                    $date = date('d.m.Y', strtotime($date_start));
                    $time = date('H:i:s', strtotime($date_start));
                    $q->where(DB::raw("STR_TO_DATE(data,'%d.%m.%Y')"), DB::raw("STR_TO_DATE('".$date."','%d.%m.%Y')"));
                    $q->where('time', '>=', $time);
                });
            });
        }

        // фильтрация по дате доставки конец
        if ( isset($_COOKIE['orderFilter_date_end'])) {
            $date_end = $_COOKIE['orderFilter_date_end'];
            $orders->where(function ($q) use($date_end) {
                $date = date('d.m.Y', strtotime($date_end));
                $q->orWhere(DB::raw("STR_TO_DATE(data,'%d.%m.%Y')"), '<', DB::raw("STR_TO_DATE('".$date."','%d.%m.%Y')"));
                $q->orWhere(function ($q) use($date_end) {
                    $date = date('d.m.Y', strtotime($date_end));
                    $time = date('H:i:s', strtotime($date_end));
                    $q->where(DB::raw("STR_TO_DATE(data,'%d.%m.%Y')"), DB::raw("STR_TO_DATE('".$date."','%d.%m.%Y')"));
                    $q->where('time', '<=', $time);
                });
            });
        }

        // фильтрация по ближайшему времени
        if (isset($_COOKIE['orderFilter_soon'])) {
            $full_data = date("d.m.Y H:i:s", strtotime("+6 hours", strtotime(date("d.m.Y H:i:s"))));
            $current_data = date("d.m.Y");
            $orders->where(function ($q) use($full_data, $current_data) {
                $q->orWhere(DB::raw("STR_TO_DATE(data,'%d.%m.%Y')"), '<=', DB::raw("STR_TO_DATE('".$current_data."','%d.%m.%Y')"));
                $q->orWhere(function ($q) use($full_data) {
                    $date = date('d.m.Y', strtotime($full_data));
                    $time = date('H:i:s', strtotime($full_data));
                    $q->where(DB::raw("STR_TO_DATE(data,'%d.%m.%Y')"), '=', DB::raw("STR_TO_DATE('".$date."','%d.%m.%Y')"));
                    $q->where('time', '<=', $time);
                });
            });
        }

        // фильтрация по сумме начало
        if ( isset($_COOKIE['orderFilter_summa_start'])) {
            $summa_start = $_COOKIE['orderFilter_summa_start'];
            $orders->where('summa', '>=', $summa_start);
        }

        // фильтрация по сумме доставки начало
        if ( isset($_COOKIE['orderFilter_driver_summa_start'])) {
            $driver_summa_start = $_COOKIE['orderFilter_driver_summa_start'];
            $orders->where('driver_summ', '>=', $driver_summa_start);
        }

        // фильтрация по сумме конец
        if ( isset($_COOKIE['orderFilter_summa_end'])) {
            $summa_end = $_COOKIE['orderFilter_summa_end'];
            $orders->where('summa', '<=', $summa_end);
        }

        // фильтрация по сумме доставки конец
        if ( isset($_COOKIE['orderFilter_driver_summa_end'])) {
            $driver_summa_end = $_COOKIE['orderFilter_driver_summa_end'];
            $orders->where('driver_summ', '<=', $driver_summa_end);
        }

        // фильтрация по номеру дисконтной карты
        if ( isset($_COOKIE['orderFilter_discard_number'])) {
            $discard_number = $_COOKIE['orderFilter_discard_number'];
            $orders->where('discount_card', $discard_number);
        }

        // фильтрация по наличию дисконтной карты
        if ( isset($_COOKIE['orderFilter_discard'])) {
            $orders->where('discount_card', '!=', '');
        }

        // фильтрация по типу заказа экспресс
        if ( isset($_COOKIE['orderFilter_express'])) {
            $orders->where('express', 1);
        }

        // фильтрация по типу акция
        if ( isset($_COOKIE['orderFilter_akciya'])) {
            $orders->where('akciya', 1);
        }

        // фильтрация по доставке или самовывозу
        if ( isset($_COOKIE['orderFilter_searchdos'])) {
            $searchdos = json_decode($_COOKIE['orderFilter_searchdos']);
            $orders->whereIn('express', $searchdos);
        }

        // фильтрация по типу заказа
        if ( isset($_COOKIE['orderFilter_order_type'])) {
            $order_type = json_decode($_COOKIE['orderFilter_order_type']);
            $orders->whereIn('type', $order_type);
        }

        // фильтрация по типу отчёта
        if ( isset($_COOKIE['orderFilter_report_type'])) {

        }

        // фильтрация по полю поиска
        if ( isset($_COOKIE['orderFilter_search'])) {
            $search = $_COOKIE['orderFilter_search'];
            $orders->where(function ($q) use($search) {
                $q->orWhere('orders.number', 'LIKE', "%".$search."%");
                $q->orWhere('orders.otkuda', 'LIKE', "%".$search."%");
                $q->orWhere('orders.discount', 'LIKE', "%".$search."%");
                $q->orWhere('orders.adres', 'LIKE', "%".$search."%");
                $q->orWhere('orders.send_phone', 'LIKE', "%".$search."%");
                $q->orWhere('orders.phone', 'LIKE', "%".$search."%");
                $q->orWhere('orders.polname', 'LIKE', "%".$search."%");
                $q->orWhere('orders.polphone', 'LIKE', "%".$search."%");
                $q->orWhere('orders.driver', 'LIKE', "%".$search."%");
                $q->orWhere('orders.email', 'LIKE', "%".$search."%");
                $q->orWhere('orders.name', 'LIKE', "%".$search."%");
                $q->orWhere('orders.internet', 'LIKE', "%".$search."%");
                $q->orWhere('orders.comment', 'LIKE', "%".$search."%");
                $q->orWhere('orders.summa', 'LIKE', "%".$search."%");
            });
        }

        $orders->orderByDesc('orders.status');
        $orders->orderByDesc('orders.dobavil_api');
        $orders->orderBy(DB::raw('STR_TO_DATE(orders.data,\'%d.%m.%Y\')'));
        $orders->orderBy('orders.time');
        $orders->orderBy('orders.id');
        $orders->groupBy('orders.id');

        $result = $orders->get();

        return $result;
    }

    public static function createEmptyOrder()
    {
        $user = Auth::user();
        $nextOrderNumber = Order::max('number') + 1;
        $order = new self;
        $order->number = $nextOrderNumber;
        $order->type_driver = 0;
        $order->status = 3;
        $order->modifed = $user->id;
        $order->sotrudnik = $user->id;
        $order->dobavil = $user->id;
        $order->dateadd = date('Y-m-d H:i:s');
        $order->time = '00:00:00';
        $order->comment = '';
        $order->rasp = '';
        $order->dis_ras = 0;
        $order->tocka_id = 0;
        $order->seller_fio = '';
        $order->driver_done = 0;
        $order->save();

        return $order;
    }

    public static function getOnceOrder($number)
    {
        $order = self::select(
            'orders.*',
            'brand.short_name as brand_short_name'
        )
            ->leftJoin('cities', 'cities.id', '=', 'orders.tocka_city_id')
            ->leftJoin('brand', 'brand.id', '=', 'orders.brand_id')
            ->where('orders.number', $number)
            ->with('clientrate')
            ->with('photos')
            ->with('point')
            ->with('courier.getShtat')
            ->with('comments.user')
            ->first();

        return $order;
    }

    public static function getChangedOrder($number)
    {
        $orders = self::getOnceOrderWithJoin();
        $orders->where('orders.number', $number);
        $orders->groupBy('orders.id');
        $result = $orders->first();

        return $result;
    }
}
