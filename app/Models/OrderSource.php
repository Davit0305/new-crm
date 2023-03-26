<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderSource
 * @property int $id
 * @property string $name
 * @property int $enabled
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class OrderSource extends Model
{
    protected $table = 'istochnik';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'enabled'
    ];

    protected $appends = [];

}
