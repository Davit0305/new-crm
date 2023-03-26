<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Point
 * @property int $id
 * @property string $name
 * @property int $city_id
 * @property int $raion
 * @property string $adres
 * @property string $sms_adres
 * @property string $phone
 * @property string $sms_phone
 * @property string $short_number
 * @property string $pano
 * @property string $yandex
 * @property int $bonus
 * @property string $bonus_date
 * @property int $discount
 * @property int $brand_id
 * @property int $modifed
 * @property int $enabled
 * @property int $3dtour
 * @property string $gu_id
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Point extends Model
{
    protected $table = 'tochka';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'city_id',
        'raion',
        'adres',
        'sms_adres',
        'phone',
        'sms_phone',
        'short_number',
        'pano',
        'yandex',
        'bonus',
        'bonus_date',
        'discount',
        'brand_id',
        'modifed',
        'enabled',
        '3dtour',
        'gu_id'
    ];

    protected $appends = ['full_name', 'full_name_short'];

    public function city()
    {
        return $this->hasOne('App\Models\City', 'id', 'city_id');
    }

    public function brand()
    {
        return $this->hasOne('App\Models\Brand', 'id', 'brand_id');
    }

    public function getFullNameAttribute()
    {
        return $this->name." (".$this->sms_adres.") [".$this->brand->short_name."]";
    }

    public function getFullNameShortAttribute()
    {
        return $this->name." [".$this->brand->short_name."]";
    }

    public static function getPointSearch($search)
    {
        $points = self::select(
            'tochka.*',
            'brand.short_name as brand_name'
        )->leftJoin('brand', 'brand.id', '=', 'tochka.brand_id')->where('tochka.enabled', 1);

        $points->where(function ($q) use($search) {
            $q->orWhere('brand.short_name', 'LIKE', "%".$search."%");
            $q->orWhere('tochka.name', 'LIKE', "%".$search."%");
            $q->orWhere('tochka.sms_adres', 'LIKE', "%".$search."%");
        });

        $points->orderBy('tochka.name');
        $result = $points->get();

        return $result;
    }
}
