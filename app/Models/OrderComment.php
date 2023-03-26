<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderComment
 * @property int $id
 * @property string $text
 * @property int $user_id
 * @property int $order_id
 * @property int $com_type
 * @property string $date_add
 * @property string $date_modified
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class OrderComment extends Model
{
    protected $table = 'order_comments';

    public $timestamps = false;

    protected $fillable = [
        'text',
        'user_id',
        'order_id',
        'com_type',
        'date_add',
        'date_modified',
    ];

    protected $appends = ['date_rus', 'date_edit_rus'];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function order()
    {
        return $this->hasOne('App\Models\Order', 'id', 'order_id');
    }

    public function getDateRusAttribute()
    {
        return date('H:i d.m.Y', strtotime($this->date_add));
    }

    public function getDateEditRusAttribute()
    {
        return date('H:i d.m.Y', strtotime($this->date_modified));
    }

}
