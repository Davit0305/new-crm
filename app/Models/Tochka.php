<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tochka extends Model
{
    use HasFactory;

    protected $table = 'tochka';

    public $timestamps = false;

    protected $fillable = [
        'name'
        , 'raion'
        , 'city_id'
        , 'adres'
        , 'sms_adres'
        , 'phone'
        , 'sms_phone'
        , 'short_number'
        , 'pano'
        , 'yandex'
        , 'bonus'
        , 'bonus_date'
        , 'discount'
        , 'brand_id'
        , 'modifed'
        , 'enabled'
        , 'gu_id'
    ];

    public function modifiedUser(){
//        "SELECT w.name as name,
//										w.surname as surname,
//										w.middlename as middlename
//										FROM worker as w
//										LEFT JOIN user as u ON w.id=u.worker_id
//										WHERE u.id={$mod_dis}";
        return $this->hasOneThrough(Worker::class,User::class,'id','id','modifed','worker_id');
    }

    public function raiony(){
        return $this->hasMany(Raiony::class,'city_id','city_id')->orderBy('name');
    }
}
