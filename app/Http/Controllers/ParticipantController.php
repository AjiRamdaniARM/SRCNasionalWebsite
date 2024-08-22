<?php

namespace App\Http\Controllers;

use App\Exports\ParticipantExport;
use App\Models\Invoice;
use App\Models\Participant;
use App\Models\Race;
use App\Models\TableSesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $race)
    {
        $cont = Race::findOrFail($race);

        return view('dashboard.race.participant.index', compact('cont', 'participants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $race = Race::with(['participants'])->findOrFail($id);

        return Excel::download(new ParticipantExport($race->id), $race->slug.'.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function peserta($id)
    {
        $race = Race::where('id', $id)->first();
        $getPesertaLomba = DB::table('participants')
            ->join('invoices', 'participants.invoice_id', '=', 'invoices.id')
            ->join('users', 'invoices.user_id', '=', 'users.id')
            ->where('participants.race_id', $id)
            ->select('participants.*', 'participants.id as id_peserta', 'participants.name as peserta', 'invoices.name as invoice_nama', 'users.*')
            ->get();

        // $getPesertaLomba = Participant::where('race_id', $id)->get();
        return view('dashboard.participant.peserta.pesertaLomba', compact('getPesertaLomba', 'race'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $race = Race::all();
        $invoices = Invoice::where('user_id', $user->id)->get();
        $invoiceIds = $invoices->pluck('id')->toArray();

        $participants = Participant::whereIn('invoice_id', $invoiceIds)
            ->with(['race.category', 'projectonlines'])
            ->get();

        // // Filter by race
        // if ($request->has('race') && $request->race != '') {
        //     $participantsQuery->where('race_id', $request->race);
        // }

        // $participants = $participantsQuery->get();

        // if ($request->ajax()) {
        //     return view('dashboard.participant.show.participant_list', compact('participants'))->render();
        // }

        return view('dashboard.participant.show.index', compact('race', 'participants'));
    }

    public function editParticipants(Request $request, $id_peserta)
    {
        // Fetch the participant by ID
        $participant = Participant::findOrFail($id_peserta);

        // Update participant attributes with data from the request
        $participant->name = $request->name;
        $participant->kelas = $request->kelas;

        // Save the updated participant
        $participant->save();

        return redirect()->back()->with('message', 'Data berhasil di edit');

    }

    public function delete($id_peserta)
    {
        $getParticipants = Participant::findOrFail($id_peserta);
        $getParticipants->delete();

        return redirect()->back()->with('message', 'Data berhasil di delete');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function table (Request $request,$id) {
        $race = Race::where('id', $id)->first();

         if ($request->ajax()) {
            $previousCommunity = null; // Pastikan ini berada di luar fungsi callback
            $previousMaps = null; // Pastikan ini berada di luar fungsi callback

            $query = Participant::with(['invoice.user'])
                ->where('race_id', $id)
                ->select('users.community', 'participants.name as peserta', 'users.address as maps', 'invoices.*','participants.id as id_peserta', 'table_sesis.sesi', 'table_sesis.duration', 'table_sesis.waktu_awal','table_sesis.waktu_akhir')
                ->join('invoices', 'participants.invoice_id', '=', 'invoices.id')
                ->join('users', 'invoices.user_id', '=', 'users.id')
                ->leftJoin('table_sesis', 'participants.id_sesi', '=', 'table_sesis.id');

                return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('community', function ($query) use (&$previousCommunity) {
                    if ($query->community != $previousCommunity) {
                        $previousCommunity = $query->community;
                        return $query->community;
                    }
                    return '';
                })
                ->addColumn('maps', function ($query) use (&$previousMaps) {
                    if ($query->maps != $previousMaps) {
                        $previousMaps = $query->maps;
                        return $query->maps;
                    }
                    return '';
                })
                ->addColumn('peserta', function ($query) {
                    return $query->peserta;
                })
                ->addColumn('sesi', function ($query) {
                    $sesiOptions = TableSesi::all();
                    $dropdown = '<select class="selectSesi" data-id_peserta="'.$query->id_peserta.'">'; // Perhatikan perubahan disini
                    foreach ($sesiOptions as $sesi) {
                        $selected = $query->sesi_id == $sesi->id ? 'selected' : '';
                        $dropdown .= '<option value="'.$sesi->id.'" '.$selected.'>'.$sesi->sesi.'</option>';
                    }
                    $dropdown .= '</select>';
                    return $dropdown;
                })
                ->addColumn('sesis', function ($query) {
                    return $query->sesi; // Kolom waktu akan kosong jika sesi belum dipilih
                })
                ->addColumn('duration', function ($query) {
                    return $query->duration; // Kolom waktu akan kosong jika sesi belum dipilih
                })
                ->addColumn('waktu_awal', function ($query) {
                    return $query->waktu_awal; // Kolom waktu akan kosong jika sesi belum dipilih
                })
                ->addColumn('waktu_akhir', function ($query) {
                    return $query->waktu_akhir; // Kolom waktu akan kosong jika sesi belum dipilih
                })
                ->filterColumn('community', function($query, $keyword) {
                    $query->where('users.community', 'like', "%{$keyword}%");
                })
                ->filterColumn('maps', function($query, $keyword) {
                    $query->where('users.address', 'like', "%{$keyword}%");
                })
                ->filterColumn('peserta', function($query, $keyword) {
                    $query->where('participants.name', 'like', "%{$keyword}%");
                })
                ->filterColumn('sesis', function($query, $keyword) {
                    $query->where('table_sesis.sesis', 'like', "%{$keyword}%");
                })
                ->rawColumns(['community', 'maps', 'peserta', 'sesis','duration' ,'sesi','waktu_awal','waktu_akhir'])
                ->toJson();

        }
        return view('dashboard.participant.peserta.tablePeserta', compact('race'));
    }
    public function getSesiDetails(Request $request)
{
     // Cari peserta berdasarkan ID
     $participant = Participant::where('id',$request->peserta_id)->firstOrFail();

     // Update id_sesi jika peserta ditemukan
     if ($participant) {
         $participant->id_sesi = $request->sesi_id;
         $participant->save();

         // Ambil data sesi untuk merespons ke client
         $sesi = TableSesi::find($request->sesi_id);

         return response()->json([
             'status' => 'success',
             'waktu' => $sesi->waktu_awal . ' - ' . $sesi->waktu_akhir,
             'durasi' => $sesi->duration,
         ]);
     }

     return response()->json(['status' => 'error', 'message' => 'Peserta tidak ditemukan'], 404);
}


}
