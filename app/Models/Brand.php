<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Brand
 * @property int $id
 * @property string $name
 * @property string $short_name
 * @property string $sms_sender_name
 * @property string $site
 * @property string $phone
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Brand extends Model
{
    protected $table = 'brand';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'short_name',
        'sms_sender_name',
        'site',
        'phone'
    ];

    protected $appends = [];
}
