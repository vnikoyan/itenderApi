<?php

namespace App\Exports;

use App\Models\Procurement\Procurement;
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

class PlanExport implements FromView, WithColumnWidths, WithDefaultStyles, WithEvents
{

    protected $id;

    function __construct($id) {
        $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('exports.plan', [
            'plan' => Procurement::with('planRows')->with('organisation')->find($this->id),
            'purchase_types' => ['ՄԱ', 'ՄԱ*', 'ՀՄԱ', 'ԲՄ', 'ՀԲՄ', 'ԳՀ', 'ԷԱՃ']
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 30,
            'C' => 10,
            'D' => 10,
            'E' => 10,
            'F' => 10,
            'G' => 15,
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
