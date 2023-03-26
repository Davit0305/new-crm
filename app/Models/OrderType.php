<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderType
 * @property int $id
 * @property string $name
 * @property string $short_name
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class OrderType extends Model
{
    protected $table = 'order_types';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'short_name'
    ];

    protected $appends = [];

}
