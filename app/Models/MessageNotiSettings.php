<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageNotiSettings extends Model
{
    use HasFactory;

    protected $table = 'message_noti_settings';

    public $timestamps = false;

    protected $guarded = [];
}
