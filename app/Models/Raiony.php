<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raiony extends Model
{
    use HasFactory;


    protected $table = 'raiony';

    public $timestamps = false;

    public $guarded = [];

    public function tochka(){
        return $this->belongsTo(Tochka::class,'city_id','city_id');
    }
}
