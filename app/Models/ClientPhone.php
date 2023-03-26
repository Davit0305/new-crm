<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClientPhone
 * @property int $id
 * @property string $phone
 * @property int $active
 * @property string $date_add
 * @property int $client_id
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ClientPhone extends Model
{
    protected $table = 'client_phone';

    public $timestamps = false;

    protected $fillable = [
        'phone',
        'active',
        'date_add',
        'client_id',
    ];

    public function client()
    {
        return $this->hasOne('App\Models\Client', 'id', 'client_id');
    }
}
