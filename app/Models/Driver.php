<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Driver
 * @property int $id
 * @property int $worker_id
 * @property string $bs_id
 * @property string $avto
 * @property string $nomer
 * @property string $name
 * @property string $phone
 * @property string $push_token
 * @property int $shtat
 * @property int $city_id
 * @property int $enabled
 * @property int $allow_send_sms
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Driver extends Model
{
    protected $table = 'drivers';

    public $timestamps = false;

    protected $fillable = [
        'worker_id',
        'bs_id',
        'avto',
        'nomer',
        'name',
        'phone',
        'push_token',
        'shtat',
        'city_id',
        'enabled',
        'allow_send_sms'
    ];

    protected $appends = [];

    public function worker()
    {
        return $this->hasOne('App\Models\Worker', 'id', 'worker_id');
    }

    public function getShtat()
    {
        return $this->hasOne('App\Models\DriverType', 'id', 'shtat');
    }

    public static function getDriversFilter($search)
    {
        $drivers = self::select(
            'drivers.*',
            'worker.name as worker_name',
            'worker.surname as worker_surname',
            'worker.middlename as worker_middlename',
            'worker.in_shift as in_shift'
        )
            ->leftJoin('worker', 'worker.id', '=', 'drivers.worker_id')
            ->where('drivers.enabled', 1);

        $drivers->where(function ($q) use($search) {
            $q->orWhere('worker.name', 'LIKE', "%".$search."%");
            $q->orWhere('worker.surname', 'LIKE', "%".$search."%");
        });
        $drivers->orderBy('worker.surname');
        $result = $drivers->get();

        return $result;
    }
}
