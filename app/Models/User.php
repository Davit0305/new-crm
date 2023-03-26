<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @property int $id
 * @property int $worker_id
 * @property int $enabled_dis
 * @property int $r_dis
 * @property int $r_den
 * @property int $r_hfo
 * @property int $r_nom
 * @property int $r_tmc
 * @property int $r_prz
 * @property int $r_toc
 * @property int $r_shw
 * @property int $r_buh
 * @property int $r_aut
 * @property int $r_are
 * @property int $r_kur
 * @property int $r_sot
 * @property int $r_otc
 * @property int $r_buk
 * @property int $r_adb
 * @property int $r_bar
 * @property int $r_vak
 * @property int $r_nku
 * @property string $pass
 * @property string $remember_token
 * @property string $login
 * @property string $online
 * @property string $authkey
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Таблица БД, ассоциированная с моделью.
     *
     * @var string
     */
    protected $table = 'user';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'pass',
        'admin',
        'r_dis',
        'r_den',
        'r_hfo',
        'r_nom',
        'r_tmc',
        'r_prz',
        'r_toc',
        'r_shw',
        'r_buh',
        'r_aut',
        'r_are',
        'r_kur',
        'r_sot',
        'r_otc',
        'r_buk',
        'r_adb',
        'r_bar',
        'r_vak',
        'r_nku',
        'worker_id',
        'enabled_dis',
        'online',
        'authkey',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pass',
        'remember_token',
    ];

    protected $appends = ['fio'];

    public function worker()
    {
        return $this->hasOne('App\Models\Worker', 'id', 'worker_id');
    }

    public function getAuthPassword () {

        return $this->pass;

    }

    public function getFioAttribute()
    {
        $fio = $this->worker ? $this->worker->getFio() : '';
        return $fio;
    }
}
