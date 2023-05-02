<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrganizeRowsExport implements FromView, WithColumnWidths, WithDefaultStyles, WithEvents
{

    protected $rows;

    function __construct($rows) {
        $this->rows = $rows;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('exports.rows', [
            'rows' => $this->rows,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 30,
            'F' => 30,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 15,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $number = $event->sheet->getDelegate()->getHighestRow();
                $character = $event->sheet->getDelegate()->getHighestColumn();
                $event->sheet->styleCells(
                    "A1:$character$number",
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ]
                    ]
                );
                $event->sheet->styleCells(
                    "A3:$character$number",
                    [
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => '000000'],
                            ],
                        ]
                    ]
                );
            },
            // BeforeSheet::class => function (BeforeSheet $event) {
            //     $event->sheet
            //         ->getPageSetup()
            //         ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            // },
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return $defaultStyle->getAlignment()->applyFromArray(
            array('horizontal' => 'center')
        );
    }
}