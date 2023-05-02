<?php

namespace App\Services\MiniServiceBioSmartHttp;
use DateTime;
use phpQuery;

class CDelay
{
    public $bsId, $number, $date, $delay, $plan, $fact, $penalty, $moot;

    function __construct($array)
    {
        $this->bsId = $array['bsId'];
        $this->number = $array['number'];
        $this->date = $array['date'];
        $this->delay = $array['delay'];
        $this->plan = $array['plan'];
        $this->fact = $array['fact'];
        $this->penalty = $array['penalty'] ?? 0;
        $this->moot = $array['moot'] ?? 0;

        $this->setPenalty();
        $this->setMoot();
    }

    function setPenalty()
    {
        if ($this->number < 4 && $this->delay->minute <= 10)
            $this->penalty = 0;

        elseif ($this->number < 4 && $this->delay->minute > 10 && $this->delay->minute <= 30)
            $this->penalty = 100;

        elseif ($this->number >= 4 && $this->delay->minute <= 30)
            $this->penalty = 100;
        else
            $this->penalty = 200 * ceil(($this->delay->minute) / 60);

        if ($this->penalty > 500) $this->penalty = 500;
    }

    function setMoot()
    {
        if ($this->delay->minute >= 30) $this->moot = 1;
    }

    static function getByMonth($month, $year, $folder)
    {
        $date = new DateTime("01.$month.$year");

        $repotName = rawurlencode('Опоздания_по_сотрудникам_за_период_с_' . $date->format('01_m_Y') . '_по_' . $date->format('t_m_Y'));
//        $repotName = rawurlencode('Фото_сотрудников_за_период_с_11_01_2023_по_11_01_2023_20230111093630');

        $document = phpQuery::newDocument(file_get_contents(Constant::biosmartHttp('/') . "?folder=$folder&find=$repotName"));
        $trWorker = pq($document)->find('td.s2-0');
        $monthData = (object)[
            'workers' => [],
            'total' => (object)[
                'count' => 0,
                'time' => (object)[
                    'value' => 0,
                    'formated' => ''
                ],
                'penalty' => (object)[
                    'value' => 0,
                    'formated' => ''
                ]
            ]
        ];

        foreach ($trWorker->elements as $key => $workerElement) {
            $arWorker = explode(' ', $workerElement->textContent);

            $line = pq($workerElement)->parent()->next()->next();
            $end = false;
            $count = 1;
            $delays = [];
            $total = (object)['count' => 0, 'time' => 0, 'penalty' => 0];

            while ($end == false) {
                $arLine = $line->find('td')->elements;

                $arDelayTime = explode(':', $arLine[1]->textContent);
                $countMinutesInHours = 60 * intval($arDelayTime[0]) + intval($arDelayTime[1]);

                //$delay = new CTime($arLine[1]->textContent);

                if ($arLine[1]->textContent != '00:00' && strlen($arLine[2]->textContent) > 3) {
                    $arDelay = [
                        'bsId' => $arWorker[0],
                        'number' => $count,
                        'date' => $arLine[0]->textContent,
                        'delay' => (object)['minute' => $countMinutesInHours, 'formated' => $arLine[1]->textContent],
                        'plan' => $arLine[2]->textContent,
                        'fact' => $arLine[3]->textContent
                    ];


                    $delay = new CDelay($arDelay);

                    $total->count = $count;
                    $total->time = $total->time + $delay->delay->minute;
                    $total->penalty = $total->penalty + $delay->penalty;

                    $delays[] = $delay;

                    $count++;
                }

                $line = pq($line)->next();
                $nextline = pq($line)->next();

                if (count($nextline->find('td')->elements) < 4 || $count > 40 || ($line->find('td')->elements[2]->textContent == '' && $line->find('td')->elements[3]->textContent == ''))
                    break;
            }

            $total->time = (object)[
                'formated' => CTime::formatMinutes($total->time),
                'minute' => $total->time
            ];
            $total->penalty = (object)[
                'formated' => $total->penalty . "&nbsp;руб.",
                'value' => $total->penalty
            ];
            if (count($delays) > 0) {
                if (isset($arWorker[3])) {
                    $secondName = $arWorker[3];
                } else {
                    $secondName = '';
                }
                $monthData->workers[$arWorker[0]] = (object)[
                    'worker' => (object)[
                        'bsId' => $arWorker[0],
                        'lastName' => $arWorker[1],
                        'name' => $arWorker[2],
                        'secondName' =>  $secondName,
                        'fio' => $arWorker[1] . ' ' . $arWorker[2] . ' ' . $secondName,
                        'shortFio' => $arWorker[1] . ' ' . mb_substr($arWorker[2], 0, 1) . '.' .( !empty($secondName) ? mb_substr($secondName, 0, 1) : '') . '.',
                        'appointment' => CDBUser::getAppointmentByBsId('bs' . $arWorker[0])
                    ],
                    'delays' => $delays,
                    'total' => $total
                ];

                $monthData->total->count = $monthData->total->count + $total->count;
                $monthData->total->time->value = $monthData->total->time->value + $total->time->minute;
                $monthData->total->penalty->value = $monthData->total->penalty->value + $total->penalty->value;
            }

        }

        $monthData->total->time->formated = CTime::formatMinutes($monthData->total->time->value);
        $monthData->total->penalty->formated = $monthData->total->penalty->value . "&nbsp;руб.";
        return $monthData;
    }

    static function getPenaltyOrderHtml($workerDelaysOffice, $date, $curDate, $prefix)
    {
        if ($prefix == 'Офис' || $prefix == 'БС') {
            $blank = 'blank_bs.jpg';
            $pageBreaks = [22, 64, 106, 148, 190, 232, 274];
            $organizationName = 'торговой сети "Букет столицы"';
        } elseif ($prefix == 'Пион') {
            $blank = 'blank_pion.jpg';
            $pageBreaks = [22, 64, 106, 148, 190, 232, 274];
            $organizationName = 'цветочных магазинов "Пион"';
        } elseif ($prefix == 'Клумба') {
            $blank = 'blank_klumba.jpg';
            $pageBreaks = [20, 62, 104, 146, 188, 230, 272];
            $organizationName = 'ЦБ "Клумба"';
        } elseif ($prefix == 'Цветоbaza') {
            $blank = 'blank_cvetobaza.jpg';
            $pageBreaks = [20, 62, 104, 146, 188, 230, 272];
            $organizationName = '"ЦветоBAZA"';
        } elseif ($prefix == 'FloraPark') {
            $blank = 'blank_florapark.jpg';
            $pageBreaks = [20, 62, 104, 146, 188, 230, 272];
            $organizationName = '"FloraPark"';
        }

        $textPenaltyOrder = "
            <!DOCTYPE HTML>
            <html>
                <head>
                    <style>
                        body { font-size:12px; font-family:Arial; text-align:left; margin:0; padding:0; font-size:16px; }
                        body p { font-size:14pt; }
                        .headerImg { width:100%; }
                        .container { display:inline-block; width:900px; padding:30px 50px 30px 100px; }
                            .header {  }
                                h1 { text-align:center; }
                                header .dateNumber { }
                                    header .dateNumber .date { float:left; }
                                    header .dateNumber .number { float:right; }
                                    header .dateNumber .clear { clear:both; }
                                .header p.order { text-align:center; text-transform: uppercase; }
                            .maintable { border-collapse: collapse; width:100%; }
                                .maintable th,
                                .maintable td { border:1px solid #999; text-align:left; padding:5px 10px; }
                                .maintable th { text-align:center; }
                                .maintable .number { text-align:center; }
                                .maintable .penalty { text-align:right; }
                            .page-break { height:60px; }
                            .signs { padding-top:60px; }
                                .signs .line { border-bottom:1px solid #555; }
                    </style>
                    <meta charset='utf-8'>
                </head>
                <body>
                    <img class='headerImg' src='https://crm.ksuz.ru/cron/reports/delay_report/$blank'>
        ";

        if (count($workerDelaysOffice->workers) > 0) {
            $textPenaltyOrder .= "
                        <div class='container'>
                            <header>
                                <h1>ПРИКАЗ</h1>
                                <div class='dateNumber'>
                                    <span class='date'>" . $curDate->format('d') . "&nbsp;" . CTime::rusMonthName((int)$curDate->format('m'), 'vin') . "&nbsp;" . $curDate->format('Y') . "</span>
                                    <span class='number'>№&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;OK</span>
                                    <div class='clear'></div>
                                </div>
                                <h3><i>О наложении штрафных санкций на сотрудников $organizationName за опоздания в " . CTime::rusMonthName($date->format('m'), 'pred') . " " . $date->format('Y') . "&nbsp;г. </i></h3>
                                <p>В целях укрепления трудовой дисциплины и достижения стабильности в работе сотрудников $organizationName, согласно приказу № 35-ОК от 26.03.2014 г.</p>
                                <p class='order'><i>Приказываю:</i></p>
                                <p>1. Применить штрафные санкции за опоздания в " . CTime::rusMonthName($date->format('m'), 'pred') . " " . $date->format('Y') . " г. к следующим сотрудникам:</p>
                            </header>
                            <table class='maintable'>
                                <tr>
                                    <th class='number'>№</td>
                                    <th>Сотрудник</td>
                                    <th>Должность</td>
                                    <th class='penalty'>Штраф</td>
                                    <th>Ознакомлен</td>
                                </tr>
            ";

            $count = 0;
            $firstCount = 0;
            foreach ($workerDelaysOffice->workers as $worker) {
                $countNext = $count + 1;
                if ($worker->total->penalty->value > 0) {
                    $textPenaltyOrder .= "
                        <tr>
                            <td class='number'>{$countNext}</td>
                            <td class='worker'>{$worker->worker->shortFio}</td>
                            <td>{$worker->worker->appointment}</td>
                            <td class='penalty'>{$worker->total->penalty->formated}</td>
                            <td></td>
                        </tr>
                    ";

                    if (in_array($count, $pageBreaks)) {
                        $firstCount = $count + 1;
                        $textPenaltyOrder .= "
                            </table>
                            <div class='page-break'>&nbsp;</div>
                            <table class='maintable'>
                                <tr>
                                    <th class='number'>№</td>
                                    <th>Сотрудник</td>
                                    <th>Должность</td>
                                    <th class='penalty'>Штраф</td>
                                    <th>Ознакомлен</td>
                                </tr>
                        ";
                    }
                    $count++;
                }
            }
            $textPenaltyOrder .= "</table>";

            $textPenaltyOrder .= "<p>2. Бухгалтеру по з/п. произвести удержание денежных средств из заработной платы выше перечисленных сотрудников.</p>";
            $textPenaltyOrder .= "<p>3. Сотрудникам ресепшена ознакомить с данным приказом указанных сотрудников.</p>";

            if ($count - $firstCount > 25) {
                $textPenaltyOrder .= "
                    <div style='page-break-after: always;'></div>
                ";
            }

            $textPenaltyOrder .= "<div class='signs'>";
//            $textPenaltyOrder .= "
//                <p>
//                    <b>Исполнительный директор</b>
//                    &nbsp;&nbsp;&nbsp;
//                    <span class='line'>
//                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//                    </span>
//                    &nbsp;&nbsp;&nbsp;
//                    ____________________
//                </p>
//            ";
            if ($prefix == 'Офис') {
                $textPenaltyOrder .= "
                <p>
                    <b>HR Директор</b>
                    &nbsp;&nbsp;&nbsp;
                    <span class='line'>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </span>
                    &nbsp;&nbsp;&nbsp;
                    ____________________
                </p>
            ";
            } else {
                $textPenaltyOrder .= "
                <p>
                    <b>Директор группы цветочных компаний</b>
                    &nbsp;&nbsp;&nbsp;
                    <span class='line'>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </span>
                    &nbsp;&nbsp;&nbsp;
                    ____________________
                </p>
            ";
            }
            $textPenaltyOrder .= "<p>&nbsp;</p>";
            $textPenaltyOrder .= "<p>С приказом ознакомлены:</p>";
            $textPenaltyOrder .= "
                <p>
                    <b>Бухгалтер  по з/п.</b>
                    &nbsp;&nbsp;&nbsp;
                    <span class='line'>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </span>
                </p>
            ";
            $textPenaltyOrder .= "</div>";

            $textPenaltyOrder .= "
                        </div>
            ";
        } else {
            $calc_month = CTime::rusMonthName($date->format('m'), 'pred');
            $textPenaltyOrder .= "<p>Штрафы отсутствуют. В {$calc_month} {$date->format('Y')} г. cотрудники {$organizationName} приходили вовремя в этом месяце.";
        }

        $textPenaltyOrder .= "
                </body>
            </html>
        ";
        return $textPenaltyOrder;
    }

    static function getDelayReportHtml($workerDelays, $date, $curDate, $prefix)
    {
        $textDelayReport = "
            <!DOCTYPE HTML>
            <html>
                <head>
                    <style>
                        body { font-size:12px; font-family:Arial; text-align:center;}
                        .container { display:inline-block; width:800px; }
                        .worker { margin-top:50px;}
                            .worker .workerName { padding: 10px 0; font-weight:bold; width:100%; font-size:20px; }
                            .worker .table table { border-collapse: collapse; border:1px solid #b7b7b7; width:100%; }
                                .worker .table table tr:nth-child(2n+3) { background:#f1f1f1; }
                                    .worker .table table td { padding:4px 10px; color:#636363; }
                                    .worker .table table tr.title td { text-align:center; color:black; border:1px solid #b7b7b7; }
                                    .worker .table table tr.moot td { color:white; background:rgba(253, 83, 83, 0.5); font-weight:bold; }

                            .worker .total { padding:10px 0; width:100%; text-align:right; }
                                .worker .total > span.time { padding:0 10px; }
                                    .worker .total > span .title { font-weight:bold; }

                            .worker > .moot { padding:10px 0; width:100%; text-align:left; line-height:1.5; }
                                .worker > .moot .mootMarker { background:rgba(253, 83, 83, 0.5); padding: 0 10px;  float:left; color:white; }
                                .worker > .moot p { margin: 0 0 0 30px;  }
                        .globalTotal { font-size:16pt; text-align:left; font-weight:bold;}
                            .globalTotal > div .title { font-weight:bold; }
                    </style>
                    <meta charset='utf-8'>
                </head>
                <body>
                    <div class='container'>
                        <header>
                            <h2>Опоздания сотрудников</h2>
                            <h2>" . CTime::rusMonthName($date->format('m')) . " " . $date->format('Y') . "</h2>
                        </header>
        ";

        foreach ($workerDelays->workers as $worker) {
            $textDelayReport .= "
                <div class='worker'>
                    <h2 class='workerName'>
                        {$worker->worker->fio}
                    </h2>
                    <div class='table'>
                        <table>
                            <tr class='title'>
                                <td rowspan=2>№</td><td rowspan=2>Дата</td><td rowspan=2>Опоздание</td><td colspan=2>Приход</td><td rowspan=2>Штраф</td>
                            </tr>
                            <tr class='title'>
                                <td>План</td><td>Факт</td>
                            </tr>
            ";
            $mootFlag = false;
            foreach ($worker->delays as $delay) {
                if ($delay->moot == 1) {
                    $mootFlag = true;
                    $classMoot = 'class=moot';
                } else
                    $classMoot = '';
                $textDelayReport .= "
                    <tr $classMoot>
                        <td>$delay->number</td>
                        <td>$delay->date</td>
                        <td>{$delay->delay->formated}</td>
                        <td>$delay->plan</td>
                        <td>$delay->fact</td>
                        <td>$delay->penalty</td>
                    </tr>
                ";
            }
            $textDelayReport .= "
                    </table>
                </div>
                <div class='total'>
                    <span class='count'>
                        <span class='title'>Опозданий : </span>
                        <span class='value'>{$worker->total->count}</span>
                    </span>
                    <span class='time'>
                        <span class='title'>Время : </span>
                        <span class='value'>{$worker->total->time->formated}</span>
                    </span>
                    <span class='penalty'>
                        <span class='title'>Штраф : </span>
                        <span class='value'>{$worker->total->penalty->formated}</span>
                    </span>
                </div>
            ";

            if ($mootFlag) {
                $textDelayReport .= "
                    <div class='moot'>
                        <span class='mootMarker'>-</span>
                        <p>У сотрудника есть подозрительные опоздания. Возможно сотрудник брал административный отпуск, но эта информация не была учтена в системе BioSmart.</p>
                    </div>
                ";
            }
            echo "</div>";
        }

        $textDelayReport .= "
            <div class='globalTotal'>
                <div class='count'>
                    <span class='title'>Всего опозданий:</span>
                    <span class='value'>" . $workerDelays->total->count . "</span>
                </div>
                <div class='time'>
                    <span class='title'>Общее время опозданий:</span>
                    <span class='value'>" . $workerDelays->total->time->formated . "</span>
                </div>
                <div class='penalty'>
                    <span class='title'>Общая сумма штрафа:</span>
                    <span class='value'>" . $workerDelays->total->penalty->formated . "</span>
                </div>
            </div>
        ";
        $textDelayReport .= "
                    </div>
                </body>
            </html>
        ";

        return $textDelayReport;
    }

    static function getByPhoto($folder)
    {
        global $mysqli_self_db;
        $repotName = rawurlencode('Фото_сотрудников_за_период_с_11_01_2023_по_11_01_2023_20230111093630.html');
        $repotName = rawurlencode('Фото_сотрудников_за_период_с_11_01_2023_по_11_01_2023');
//        $url = Constant::biosmartHttp('/').$folder."/".$repotName;
        $document = file_get_contents(Constant::biosmartHttp('/') . "?folder=$folder&find=$repotName");
        $dom = new DOMDocument();
        $dom->loadHTMLFile($document);
        $clock_num = [];
        $child_elements = $dom->getElementsByTagName('tr');
        foreach ($child_elements as $key => $child_element) {
            $clock_num[] = (int)trim($child_element->nodeValue);
        }
//        $document = phpQuery::newDocument(file_get_contents(Constant::biosmartHttp('/')."?folder=$folder&find=$repotName"));
//        $trWorker = pq($document)->find('td');
//        return file_get_contents(Constant::biosmartHttp('/')."?folder=$folder&find=$repotName");
        return $clock_num;
    }
}
