<?php

namespace App\Http\Controllers\export;

use App\Exports\AllParticipantsExport;
use App\Exports\ParticipantExport;
use App\Http\Controllers\Controller;
use App\Models\Race;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    // === function export data participants excel === //
    public function excel($id) {
        // === jalankan sistem export data dari library === //
        return Excel::download(new ParticipantExport($id), 'participants.xlsx');
    }

    public function allexcel() {
        // === jalankan sistem export data dari library === //
        return Excel::download(new AllParticipantsExport(), 'allparticipants.xlsx');
    }

    // === function export data participants pdf === //
    public function pdf($id) {
        $race = Race::where('id', $id)->first();
        $participants = DB::table('participants')
        ->join('invoices', 'participants.invoice_id', '=', 'invoices.id')
        ->join('users', 'invoices.user_id', '=', 'users.id')
        ->leftjoin('table_sesis', 'participants.id_sesi', '=', 'table_sesis.id')
        ->where('participants.race_id', $id)
        ->select('users.community', 'participants.name as peserta', 'users.address as maps','table_sesis.*')
        ->get(); // === gunakan get() untuk mendapatkan semua data === //

    // === Siapkan data yang akan dikirim ke view === //
    $data = [
        'name' => $race->name,
        'participants' => $participants
    ];

        $pdf = Pdf::loadView('components/export/pdf', $data);

        return $pdf->stream('laporan.pdf');
    }
}
