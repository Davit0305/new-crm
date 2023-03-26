<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Appointment
 * @property int $id
 * @property string $bs_id
 * @property string $name
 * @property int $enabled
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Appointment extends Model
{
    protected $table = 'appointments';

    public $timestamps = false;

    protected $fillable = [
        'bs_id',
        'name',
        'enabled'
    ];

    protected $appends = [];
}
