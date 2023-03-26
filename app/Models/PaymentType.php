<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentType
 * @property int $id
 * @property int $number
 * @property string $name
 * @property int $enabled
 * @property int $sort
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PaymentType extends Model
{
    protected $table = 'sposoby_oplaty';

    public $timestamps = false;

    protected $fillable = [
        'number',
        'name',
        'enabled',
        'sort'
    ];

    protected $appends = [];

}
