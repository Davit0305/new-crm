<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClientRate
 * @property int $id
 * @property int $client_id',
 * @property int $user_id',
 * @property string $comment',
 * @property int $order_id',
 * @property string $hashtags_id_serialized',
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ClientRate extends Model
{
    protected $table = 'clientrate';

    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'user_id',
        'comment',
        'order_id',
        'hashtags_id_serialized',
    ];

    protected $appends = ['hashtags'];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function client()
    {
        return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }

    public function order()
    {
        return $this->hasOne('App\Models\Order', 'id', 'order_id');
    }

    public function getHashtagsAttribute()
    {
        return array_values(unserialize($this->hashtags_id_serialized));
    }
}
