<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Worker
 * @property int $id
 * @property int $clock_num
 * @property string $bs_id
 * @property string $surname
 * @property string $name
 * @property string $middlename
 * @property int $gender
 * @property string $birthdate
 * @property string $phone
 * @property string $dep_id
 * @property string $job_id
 * @property string $begin_date
 * @property int $add_dis
 * @property int $mod_dis
 * @property int $enabled
 * @property int $in_shift
 * @property string $shift_time
 * @package App\Models
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Worker extends Model
{
    protected $table = 'worker';

    public $timestamps = false;

    protected $fillable = [
        'clock_num',
        'bs_id',
        'surname',
        'name',
        'middlename',
        'gender',
        'birthdate',
        'phone',
        'dep_id',
        'job_id',
        'begin_date',
        'add_dis',
        'mod_dis',
        'enabled',
        'in_shift',
        'shift_time'
    ];

    protected $appends = ['fio'];

    public function department()
    {
        return $this->hasOne('App\Models\Department', 'bs_id', 'dep_id');
    }

    public function position()
    {
        return $this->hasOne('App\Models\Appointment', 'bs_id', 'job_id');
    }

    public function getFioAttribute()
    {
        return $this->getFio();
    }

    public function getFio($short = false)
    {
        if ($short === true){
            return $this->getShortFio();
        } else {
            return $this->surname . ' ' . $this->name . ' ' . $this->middlename;
        }
    }

    public function getShortFio(){
        return $this->surname . ' ' . mb_substr($this->name,0,1) . '.' . mb_substr($this->middlename,0,1).'.';
    }

    public static function getWorkersWithCookieFilter($exel = false)
    {
        $point_dep_ids = ['bs67901431','bs67880556','bs67207104','bs67880555'];
        if($exel === true) {
            $workers = self::select(
                'worker.id',
                DB::raw('CONCAT(worker.surname, \' \', worker.name, \' \', worker.middlename) AS fio'),
                'departments.name as department_name',
                'appointments.name as job_name',
                DB::raw('(CASE WHEN worker.enabled=1 THEN \'Работает\' ELSE \'Уволен\' END) as status')
            )
                ->leftJoin('departments', 'departments.bs_id', '=', 'worker.dep_id')
                ->leftJoin('appointments', 'appointments.bs_id', '=', 'worker.job_id')
                ->whereNotIn('dep_id', $point_dep_ids);
        } else {
            $workers = self::select(
                'worker.*',
                'departments.name as department_name',
                'appointments.name as job_name'
            )
                ->leftJoin('departments', 'departments.bs_id', '=', 'worker.dep_id')
                ->leftJoin('appointments', 'appointments.bs_id', '=', 'worker.job_id')
                ->whereNotIn('dep_id', $point_dep_ids);
        }

        if ( isset($_COOKIE['workerFilter_enabled'])) {
            $enabled = $_COOKIE['workerFilter_enabled'];
            if($enabled == 2) {
                $workers->where('worker.enabled', 1);
            } elseif($enabled == 3) {
                $workers->where('worker.enabled', 0);
            }
        } else {
            $workers->where('worker.enabled', 1);
        }

        if ( isset($_COOKIE['workerFilter_job'])) {
            $job = $_COOKIE['workerFilter_job'];
            $workers->where('worker.job_id', $job);
        }

        if ( isset($_COOKIE['workerFilter_dep'])) {
            $dep = $_COOKIE['workerFilter_dep'];
            $workers->where('worker.dep_id', $dep);
        }

        // фильтрация по полю поиска
        if ( isset($_COOKIE['workerFilter_search'])) {
            $search = $_COOKIE['workerFilter_search'];
            $workers->where(function ($q) use($search) {
                $q->orWhere('worker.name', 'LIKE', "%".$search."%");
                $q->orWhere('worker.surname', 'LIKE', "%".$search."%");
                $q->orWhere('worker.middlename', 'LIKE', "%".$search."%");
                $q->orWhere('worker.phone', 'LIKE', "%".$search."%");
            });
        }

        if($exel === true) {
            $workers->orderBy('fio');
        } else {
            $workers->orderBy('worker.surname');
        }

        $result = $workers->get();

        return $result;
    }


    public static function getSellerSearch($search)
    {
        $arSellersBsIds = ['bs67204997','bs67200260', 'bs67204998', 'bs67205095', 'bs67217711', 'bs67217712', 'bs67206455', 'bs67200599', 'bs67200601', 'bs67200607'];
        $workers = self::select(
            'worker.*'
        )->whereIn('job_id', $arSellersBsIds)->where('enabled', 1);

        $workers->where(function ($q) use($search) {
            $q->orWhere('worker.name', 'LIKE', "%".$search."%");
            $q->orWhere('worker.surname', 'LIKE', "%".$search."%");
            $q->orWhere('worker.middlename', 'LIKE', "%".$search."%");
        });

        $workers->orderBy('worker.surname');
        $result = $workers->get();

        return $result;
    }
    public function appointment(){
        return $this->hasOne(Appointment::class,'bs_id','job_id');
    }
}
