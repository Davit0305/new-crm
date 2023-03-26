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
class City extends Model
{
    protected $table = 'cities';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'time_zone'
    ];

    protected $appends = [];
}
