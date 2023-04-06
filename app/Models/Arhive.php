<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Arhive extends Model
{
    protected $table = 'arhive';
    public $timestamps = false;
    protected $guarded = [];
    protected $appends = [];
}
