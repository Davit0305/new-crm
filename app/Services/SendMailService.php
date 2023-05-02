<?php

namespace App\Services;

use App\Services\MiniServiceBioSmartHttp\CDelay;
use App\Services\MiniServiceBioSmartHttp\CTime;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\DateTime;

class SendMailService extends CDelay
{

    public function __construct()
    {

    }

    public function sendMonth1($request)
    {
        try {
            $start = microtime(true);
            $curDate = new DateTime();
            $date = new DateTime('-1 month');

            // задаем пути и имена html-файлов
            $htmlfileDetailOffice = '/public/cron/reports/delay_report/delay_report_office.html';
            $htmlfileDetailShopBs = '/public/cron/reports/delay_report/delay_report_shop_bs.html';
            $htmlfileDetailShopPion = '/public/cron/reports/delay_report/delay_report_shop_pion.html';
            $htmlfileDetailShopKlumba = '/public/cron/reports/delay_report/delay_report_shop_klumba.html';
            $htmlfileDetailShopCvetobaza = '/public/cron/reports/delay_report/delay_report_shop_cvetobaza.html';
            $htmlfileDetailShopFlorapark = '/public/cron/reports/delay_report/delay_report_shop_florapark.html';

            // получаем данные опозданий
            $workerDelaysOffice = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'office');
            $workerDelaysShopBs = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'shop/bs');
            $workerDelaysShopPion = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'shop/pion');
            $workerDelaysShopKlumba = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'shop/klumba');
            $workerDelaysShopCvetobaza = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'shop/cvetobaza');
            $workerDelaysShopFlorapark = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'shop/florapark');

            // формируем html текст
            $textDelayOrderOffice = CDelay::getDelayReportHtml($workerDelaysOffice, $date, $curDate, 'Офис');
            $textDelayOrderShopBs = CDelay::getDelayReportHtml($workerDelaysShopBs, $date, $curDate, 'БС');
            $textDelayOrderShopPion = CDelay::getDelayReportHtml($workerDelaysShopPion, $date, $curDate, 'Пион');
            $textDelayOrderShopKlumba = CDelay::getDelayReportHtml($workerDelaysShopKlumba, $date, $curDate, 'Клумба');
            $textDelayOrderShopCvetobaza = CDelay::getDelayReportHtml($workerDelaysShopCvetobaza, $date, $curDate, 'Цветобаза');
            $textDelayOrderShopFlorapark = CDelay::getDelayReportHtml($workerDelaysShopFlorapark, $date, $curDate, 'FloraPark');

            // записываем в html-файлы содержимое
            Storage::disk('local')->put($htmlfileDetailOffice, $textDelayOrderOffice);
            Storage::disk('local')->put($htmlfileDetailShopBs, $textDelayOrderShopBs);
            Storage::disk('local')->put($htmlfileDetailShopPion, $textDelayOrderShopPion);
            Storage::disk('local')->put($htmlfileDetailShopKlumba, $textDelayOrderShopKlumba);
            Storage::disk('local')->put($htmlfileDetailShopCvetobaza, $textDelayOrderShopCvetobaza);
            Storage::disk('local')->put($htmlfileDetailShopFlorapark, $textDelayOrderShopFlorapark);

            $as = 'Детализация опозданий сотрудников за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y') . ' (БС).html';
            $files1 = [
                ['path' => Storage::disk('local')->path($htmlfileDetailOffice),'name' => $as],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopBs),'name' => $as],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopPion),'name' => $as],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopKlumba),'name' => $as],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopCvetobaza),'name' => $as],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopFlorapark),'name' => $as],
            ];
            $files2 = [
                ['path' => Storage::disk('local')->path($htmlfileDetailShopBs),'name' => $as],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopPion),'name' => $as],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopKlumba),'name' => $as],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopCvetobaza),'name' => $as],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopFlorapark),'name' => $as],
            ];

            $subject = 'Детализация опозданий сотрудников за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y');
            $emails1 = [];
            foreach (OFFICE_DETALIZATION_SEND_EMAILS as $getter) {
                $emails1[] = $getter['email']; //$getter['name']);
            }
            $emails2 = [];
            foreach (SHOP_DETALIZATION_SEND_EMAILS as $getter) {
                $emails2[] = $getter['email']; //$getter['name']);
            }
            $body = "<p>Детализация опозданий сотрудников в прикрепленных файлах.</p></br>"
                ."<p>Это письмо сформировано и отправлено автоматически. Пожалуйста, не отвечайте на него.</p>";

            // отправляем письма --->
            $this->sendTo($date, $emails1, $files1, $subject, $start, ['body' => $body]);
            $res = $this->sendTo($date, $emails2, $files2, $subject, $start, ['body' => $body]);
            // <---
            return $res;

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse()->getBody()->getContents();
            } else {
                return $e->getMessage();
            }
        }
    }

    public function sendTo($date, $emails, $files, $subject, $start,$viewData = [])
    {

        Mail::send('emails.contact_form', $viewData, function ($message) use ($emails, $files, $subject, $date) {
            $message->to($emails)->subject($subject);
            foreach ($files as $file) {
                $message->attach($file['path'], array(
                    'as' => $file['name'], // If you want you can chnage original name to custom name
                ));
            }
        });

        $respone = 'Время выполнения скрипта: ' . (microtime(true) - $start) . ' сек.<br>';
        return $respone;
    }

    public function sendMonth3($request)
    {
        try {
        $start = microtime(true);
        $curDate = new DateTime();
        $date = new DateTime('-1 month');

        // задаем пути и имена html-файлов
        $htmlfileOrderOffice = '/public/cron/reports/delay_report/penalty_order_office.html';
        $htmlfileDetailOffice = '/public/cron/reports/delay_report/delay_report_office.html';

        // получаем данные опозданий
        $workerDelaysOffice = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'office');

        // формируем html текст
        $textPenaltyOrderOffice = CDelay::getPenaltyOrderHtml($workerDelaysOffice, $date, $curDate, 'Офис');
        $textDelayOrderOffice = CDelay::getDelayReportHtml($workerDelaysOffice, $date, $curDate, 'Офис');

        // записываем в html-файлы содержимое
        Storage::disk('local')->put($htmlfileOrderOffice, $textPenaltyOrderOffice);
        Storage::disk('local')->put($htmlfileDetailOffice, $textDelayOrderOffice);

        $files = [
           ['path' => Storage::disk('local')->path($htmlfileOrderOffice),
            'name' => 'Приказ о штрафах за '
                . CTime::rusMonthName($date->format('m'))
                . ' ' . $date->format('Y') . ' (Офис).html'
           ],
            ['path' => Storage::disk('local')->path($htmlfileDetailOffice),
                'name' => 'Детализация опозданий сотрудников за '
                    . CTime::rusMonthName($date->format('m'))
                    . ' ' . $date->format('Y') . ' (Офис).html'
            ]
        ];

        $subject = 'Приказ о штрафах за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y');
        $emails = [];
        foreach (OFFICE_DETALIZATION_SEND_EMAILS as $getter) {
            $emails[] = $getter['email']; //$getter['name']);
        }
       $body = "<p>Приказ о штрафах и детализация опозданий сотрудников в прикрепленных файлах.</p></br>"
               ."<p>Это письмо сформировано и отправлено автоматически. Пожалуйста, не отвечайте на него.</p>";

        // отправляем письма --->
        $res = $this->sendTo($date, $emails, $files, $subject, $start, ['body' => $body]);
        // <---
        return $res;

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse()->getBody()->getContents();
            } else {
                return $e->getMessage();
            }
        }
    }

    public function sendMonth10($request)
    {
//        try {
            $start = microtime(true);
            $curDate = new DateTime();
            $date = new DateTime('-1 month');

            // задаем пути и имена html-файлов
            $htmlfileOrderShopBs = '/public/cron/reports/delay_report/penalty_order_shop_bs.html';
            $htmlfileOrderShopPion = '/public/cron/reports/delay_report/penalty_order_shop_pion.html';
            $htmlfileOrderShopKlumba = '/public/cron/reports/delay_report/penalty_order_shop_klumba.html';
            $htmlfileOrderShopCvetobaza = '/public/cron/reports/delay_report/penalty_order_shop_cvetobaza.html';
            $htmlfileOrderShopFlorapark = '/public/cron/reports/delay_report/penalty_order_shop_florapark.html';
            $htmlfileDetailShopBs = '/public/cron/reports/delay_report/delay_report_shop_bs.html';
            $htmlfileDetailShopPion = '/public/cron/reports/delay_report/delay_report_shop_pion.html';
            $htmlfileDetailShopKlumba = '/public/cron/reports/delay_report/delay_report_shop_klumba.html';
            $htmlfileDetailShopCvetobaza = '/public/cron/reports/delay_report/delay_report_shop_cvetobaza.html';
            $htmlfileDetailShopFlorapark = '/public/cron/reports/delay_report/delay_report_shop_florapark.html';


        $workerDelaysShopBs = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'shop/bs');
        $workerDelaysShopPion = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'shop/pion');
        $workerDelaysShopKlumba = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'shop/klumba');
        $workerDelaysShopCvetobaza = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'shop/cvetobaza');
        $workerDelaysShopFlorapark = CDelay::getByMonth($date->format('m'), $date->format('Y'), 'shop/florapark');

        // формируем html текст
        $textPenaltyOrderShopBs = CDelay::getPenaltyOrderHtml($workerDelaysShopBs, $date, $curDate, 'БС');
        $textPenaltyOrderShopPion = CDelay::getPenaltyOrderHtml($workerDelaysShopPion, $date, $curDate, 'Пион');
        $textPenaltyOrderShopKlumba = CDelay::getPenaltyOrderHtml($workerDelaysShopKlumba, $date, $curDate, 'Клумба');
        $textPenaltyOrderShopCvetobaza = CDelay::getPenaltyOrderHtml($workerDelaysShopCvetobaza, $date, $curDate, 'Цветоbaza');
        $textPenaltyOrderShopFlorapark = CDelay::getPenaltyOrderHtml($workerDelaysShopFlorapark, $date, $curDate, 'FloraPark');
        $textDelayOrderShopBs = CDelay::getDelayReportHtml($workerDelaysShopBs, $date, $curDate, 'БС');
        $textDelayOrderShopPion = CDelay::getDelayReportHtml($workerDelaysShopPion, $date, $curDate, 'Пион');
        $textDelayOrderShopKlumba = CDelay::getDelayReportHtml($workerDelaysShopKlumba, $date, $curDate, 'Клумба');
        $textDelayOrderShopCvetobaza = CDelay::getDelayReportHtml($workerDelaysShopCvetobaza, $date, $curDate, 'Цветоbaza');
        $textDelayOrderShopFlorapark = CDelay::getDelayReportHtml($workerDelaysShopFlorapark, $date, $curDate, 'FloraPark');

        // записываем в html-файлы содержимое
        Storage::disk('local')->put($htmlfileOrderShopBs, $textPenaltyOrderShopBs);
        Storage::disk('local')->put($htmlfileOrderShopPion, $textPenaltyOrderShopPion);
        Storage::disk('local')->put($htmlfileOrderShopKlumba, $textPenaltyOrderShopKlumba);
        Storage::disk('local')->put($htmlfileOrderShopCvetobaza, $textPenaltyOrderShopCvetobaza);
        Storage::disk('local')->put($htmlfileOrderShopFlorapark, $textPenaltyOrderShopFlorapark);
        Storage::disk('local')->put($htmlfileDetailShopBs, $textDelayOrderShopBs);
        Storage::disk('local')->put($htmlfileDetailShopPion, $textDelayOrderShopPion);
        Storage::disk('local')->put($htmlfileDetailShopKlumba, $textDelayOrderShopKlumba);
        Storage::disk('local')->put($htmlfileDetailShopCvetobaza, $textDelayOrderShopCvetobaza);
        Storage::disk('local')->put($htmlfileDetailShopFlorapark, $textDelayOrderShopFlorapark);

            $files = [
                ['path' => Storage::disk('local')->path($htmlfileOrderShopBs),
                    'name' => 'Приказ о штрафах за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y') . ' (БС).html'
                ],
                ['path' => Storage::disk('local')->path($htmlfileOrderShopPion),
                    'name' => 'Приказ о штрафах за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y') . ' (Пион).html'
                ],
                ['path' => Storage::disk('local')->path($htmlfileOrderShopKlumba),
                    'name' => 'Приказ о штрафах за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y') . ' (Клумба).html'
                ],
                ['path' => Storage::disk('local')->path($htmlfileOrderShopCvetobaza),
                    'name' => 'Приказ о штрафах за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y') . ' (Цветоbaza).html'
                ],
                ['path' => Storage::disk('local')->path($htmlfileOrderShopFlorapark),
                    'name' => 'Приказ о штрафах за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y') . ' (FloraPark).html'
                ],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopBs),
                    'name' => 'Детализация опозданий сотрудников за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y') . ' (БС).html'
                ],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopPion),
                    'name' => 'Детализация опозданий сотрудников за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y') . ' (Пион).html'
                ],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopKlumba),
                    'name' => 'Детализация опозданий сотрудников за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y') . ' (Клумба).html'
                ],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopCvetobaza),
                    'name' => 'Детализация опозданий сотрудников за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y') . ' (Цветоbaza).html'
                ],
                ['path' => Storage::disk('local')->path($htmlfileDetailShopFlorapark),
                    'name' => 'Детализация опозданий сотрудников за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y') . ' (FloraPark).html'
                ],
            ];

            $subject = 'Приказ о штрафах за ' . CTime::rusMonthName($date->format('m')) . ' ' . $date->format('Y');
            $emails = [];
            foreach (SHOP_PENALTY_SEND_EMAILS as $getter) {
                $emails[] = $getter['email']; //$getter['name']);
            }
            $body = "<p>Приказ о штрафах и детализация опозданий сотрудников в прикрепленных файлах.</p></br>"
                ."<p>Это письмо сформировано и отправлено автоматически. Пожалуйста, не отвечайте на него.</p>";

            // отправляем письма --->
            $res = $this->sendTo($date, $emails, $files, $subject, $start, ['body' => $body]);
            // <---
            return $res;

//        } catch (RequestException $e) {
//            if ($e->hasResponse()) {
//                return $e->getResponse()->getBody()->getContents();
//            } else {
//                return $e->getMessage();
//            }
//        }
    }
}
