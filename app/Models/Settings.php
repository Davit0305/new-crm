<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';
    public $timestamps = false;
    protected $guarded = [];
    protected $appends = [];
}
