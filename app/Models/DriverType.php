<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DriverType
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $enabled
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class DriverType extends Model
{
    protected $table = 'type_driver';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'type',
        'enabled'
    ];

    protected $appends = [];

}
