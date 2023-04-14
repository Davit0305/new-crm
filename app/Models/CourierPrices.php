<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierPrices extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'courier_prices';
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var array
     */
    public $guarded = [];
}
