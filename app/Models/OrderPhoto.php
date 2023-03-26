<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderPhoto
 * @property int $id
 * @property int $id_zak',
 * @property int $id_dis',
 * @property string $date',
 * @property string $file'
 * @property int $type
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class OrderPhoto extends Model
{
    protected $table = 'foto';

    public $timestamps = false;

    protected $fillable = [
        'id_zak',
        'id_dis',
        'date',
        'type', // 1 - before, 2 - after
        'file'
    ];

    protected $appends = ['rus_time', 'user_fio'];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_dis');
    }

    public function worker()
    {
        return $this->hasOne('App\Models\Worker', 'id', 'id_dis');
    }

    public function getRusTimeAttribute()
    {
        return date('H:i d.m.Y', strtotime($this->date));
    }

    public function getUserFioAttribute()
    {
        if($this->user) {
            $text = 'Диспетчер: ';
            $user = $this->user->fio;
        } elseif(($this->worker)) {
            $text = 'Курьер: ';
            $user = $this->worker->fio;
        } else {
            $text = 'Неизвестный источник';
            $user = '';
        }
        return $text.$user;
    }
}
