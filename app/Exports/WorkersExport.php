<?php

namespace App\Exports;

use App\Models\Worker;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class WorkersExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        $res = Worker::getWorkersWithCookieFilter(true);
        $res->map(function ($row,$key) {
            return $row['id'] = ++$key;
        });
        return $res;
    }

    public function headings(): array
    {
        return [
            '№ п/п',
            'Фамилия Имя Отчество',
            'Отдел',
            'Должность',
            'Статус',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $alphabet       = $event->sheet->getHighestDataColumn();
                $totalRow       = $event->sheet->getHighestDataRow();
                $cellRange      = 'A1:'.$alphabet.$totalRow;
                $styleHeader = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '808080']
                        ],
                    ]
                ];
                $event->sheet->getStyle($cellRange)->applyFromArray($styleHeader);

                $event->sheet->getDelegate()->getStyle('A')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('E')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A1:E1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A1:E1')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('fec5b9');

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(20);

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(7);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
            },
        ];
    }
}
