<?php

namespace App\Services\MiniServiceBioSmartHttp;

use DateTime;

class CTime extends DateTime
{
    function countMinutesInHours()
    {
        return $this->format('H')*60 + $this->format('i');
    }

    static function formatMinutes($minute)
    {
        $day = floor($minute/1440);
        $minute = $minute - $day*1440;
        $hour = floor($minute/60);
        $minute = $minute - $hour*60;

        $return = '';

        if ($day>0) $return .= "$day дн. ";
        if ($hour>0) $return .= "$hour ч. ";
        if ($minute>0) $return .= "$minute мин. ";

        if (empty($return)) $return = 0;

        return $return;
    }

    static function rusMonthName($number,$padezh='')
    {
        $intNumber = (int)$number;

        $names = [
            [],
            ['im'=>'январь','vin'=>'января','pred'=>'январе'],
            ['im'=>'февраль','vin'=>'февраля','pred'=>'феврале'],
            ['im'=>'март','vin'=>'марта','pred'=>'марте'],
            ['im'=>'апрель','vin'=>'апреля','pred'=>'апреле'],
            ['im'=>'май','vin'=>'мая','pred'=>'мае'],
            ['im'=>'июнь','vin'=>'июня','pred'=>'июне'],
            ['im'=>'июль','vin'=>'июля','pred'=>'июле'],
            ['im'=>'август','vin'=>'августа','pred'=>'августе'],
            ['im'=>'сентябрь','vin'=>'сентября','pred'=>'сентябре'],
            ['im'=>'октябрь','vin'=>'октября','pred'=>'октябре'],
            ['im'=>'ноябрь','vin'=>'ноября','pred'=>'ноябре'],
            ['im'=>'декабрь','vin'=>'декабря','pred'=>'декабре']
        ];

        if ( $padezh=='vin' ) return $names[$intNumber]['vin'];
        elseif ( $padezh=='pred' ) return $names[$intNumber]['pred'];
        else return $names[$intNumber]['im'];
    }

    function smartTime()
    {
        $now = new DateTime();

        if ( $now->format('d.m.Y') == $this->format('d.m.Y') )
            return $this->format('H:i');
        else
            return $this->format('d.m.Y H:i');
    }

}
