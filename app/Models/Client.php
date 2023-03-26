<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Client
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string $middlename
 * @property int $gender
 * @property string $email
 * @property int $country
 * @property int $status_vip
 * @property int $status_corp
 * @property int $status_partner
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Client extends Model
{
    protected $table = 'client';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'surname',
        'middlename',
        'email',
        'gender',
        'country',
        'status_vip',
        'status_corp',
        'status_partner',
    ];

    protected $appends = [];
}
