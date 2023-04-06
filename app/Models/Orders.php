<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 * @property int $id
 * @property string $name
 * @property int $time_zone
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Orders extends Model
{
    protected $table = 'orders';

    public $timestamps = false;

    protected $guarded = [];

    protected $appends = [];
}
