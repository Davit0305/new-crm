<?php

namespace App\Services;

use App\Models\Settings;
use Illuminate\Support\Facades\DB;

class WorkerService
{

    /**
     * @throws \Exception
     */
    public function __invoke()
    {
        dd(55);
        $users_import = Settings::where('code', '=', 'users_import')->first('value');
        dd($users_import);
        if ($users_import != 1) {
            return 'Import is disabled!';
        }

        $data_xml = '';
        $arResult = [];

///////////////////////////////////////////////////
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<KRECEPT>
 <REQUEST type="5">
  <RECORD operation="0"/>
 </REQUEST>
</KRECEPT>';
//////////////////////////////////////////////////

        $xml = $xml . chr(0);
        $fp = fsockopen(env('host_name'), env('port'), $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            fwrite($fp, $xml);
            while (!empty($t = fgets($fp, 4096))) {
                $data_xml .= $t;
            }
            fclose($fp);
        }

        $xml = new \SimpleXMLElement($data_xml);

        foreach ($xml->answer as $answer) {
            foreach ($answer as $record) {
                $arRecord = ['id' => $record->attributes()->id->__toString()];
                foreach ($record as $field) {
                    $arRecord[$field->attributes()->name->__toString()] = $field->__toString();
                }
                $arResult[] = $arRecord;
            }
        }

        foreach ($arResult as $record) {
            /*
                gender - Пол (М/Ж/Неопределен)
                last_name (surname) - Фамилия
                clock_num - Табельный номер
                org_id - Id-предприятия
                begin_date - Дата начала работы (d.m.Y)
                first_name (name) - Имя
                birthdate - День рождения (d.m.Y)
                middle_name (middlename) - Отчество
                dep_id - Id-подразделения
                job_id - Id-должности
            */
            $enabled_bs_id_array[] = $record['id'];
            $count_worker = DB::table('worker')->where('bs_id', '=', $record['id'])->count();
            $birthdate = isset($record['birthdate']) ? date('Y-m-d', strtotime($record['birthdate'])) : null;
            $begin_date = isset($record['begin_date']) ? date('Y-m-d', strtotime($record['begin_date'])) : null;
            $dep_id = isset($record['dep_id']) ? $record['dep_id'] : null;
            $job_id = isset($record['job_id']) ? $record['job_id'] : null;
            $gender = isset($record['gender']) ? $record['gender'] : null;

            if ($count_worker > 0) {
                DB::table('worker')->where('bs_id', '=', $record['id'])
                    ->update([
                        'name' => $record['first_name'],
                        'surname' => $record['last_name'],
                        'middlename' => $record['middle_name'],
                        'clock_num' => $record['clock_num'],
                        'gender' => $gender,
                        'birthdate' => $birthdate,
                        'dep_id' => $dep_id,
                        'job_id' => $job_id,
                        'begin_date' => $begin_date,
                        'enabled' => 1,
                    ]);
                //echo $record[1].' - обновлено!<br>';
            } else {
                DB::table('worker')->insert([
                    'bs_id' => $record['id'],
                    'name' => $record['first_name'],
                    'surname' => $record['last_name'],
                    'middlename' => $record['middle_name'],
                    'clock_num' => $record['clock_num'],
                    'gender' => $gender,
                    'birthdate' => $birthdate,
                    'dep_id' => $dep_id,
                    'job_id' => $job_id,
                    'begin_date' => $begin_date,
                    'enabled' => 1,
                ]);
            }
        }
    }
}
