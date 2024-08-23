<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Participant;
use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesertaController extends Controller
{
    public function adminParticipants(Request $request)
    {
        $user = Auth::user();
        $race = Race::withCount(['participants',])->get();

        $invoices = Invoice::all();
        $participantsQuery = Participant::whereIn('invoice_id', $invoices->pluck('id'));

        // Filter by race
        if ($request->has('race') && $request->race != '') {
            $participantsQuery->where('race_id', $request->race);
        }

        $participants = $participantsQuery->get();

        // Calculate team participant count (assuming 1 team = 2 participants)
        foreach ($race as $races) {
            if ($races->team) {
                $races->team_participants_count = $races->participants_count / 2;
            } else {
                $races->team_participants_count = 0;
            }
        }

        if ($request->ajax()) {
            return view('dashboard.participant.peserta.participant_list', compact('participants'))->render();
        }

        return view('dashboard.participant.peserta.index', compact('race', 'participants'));
    }

}
