<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantExport implements FromView, WithStyles, WithEvents
{
    protected $id;
    public function __construct($id)
    {
        $this->id = $id;
    }
    public function view(): View
    {
        $participants = DB::table('participants')
            ->join('invoices', 'participants.invoice_id', '=', 'invoices.id')
            ->join('users', 'invoices.user_id', '=', 'users.id')
            ->join('races', 'participants.race_id', '=', 'races.id')
            ->leftjoin('table_sesis', 'participants.id_sesi', '=', 'table_sesis.id')
            ->where('participants.race_id', $this->id)
            ->select('users.community', 'participants.name as peserta', 'users.address as maps', 'races.name as race','table_sesis.*')
            ->get();
        return view('components.export.excelParticipants', [
            'participants' => $participants
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 12],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FF4CAF50']],
            ],
            'A1:I1000' => [  // Update range from 'A1:E1000' to 'A1:I1000'
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $columns = range('A', 'I');  // Update range to cover columns A to I
                foreach ($columns as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }

}
