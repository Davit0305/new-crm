<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class TempOrders extends Model
{
    protected $table = '__temp_orders';

    public $timestamps = false;

    protected $guarded = [];

}
