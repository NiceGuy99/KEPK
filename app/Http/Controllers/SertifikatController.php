<?php

namespace App\Http\Controllers;

use App\Models\PengajuanProposal;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SertifikatController extends Controller
{
    public function cetak($proposalId)
    {
        $user = Auth::user();
        $proposal = PengajuanProposal::with([
            'peneliti.profil.statusPengguna',
            'jenisPenelitian',
            'jenisPersetujuan',
            'sertifikat',
        ])->findOrFail($proposalId);

        if ($user->isPeneliti() && $proposal->peneliti_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if (!$proposal->sertifikat) {
            return back()->with('pesan_error', 'Sertifikat belum diterbitkan untuk proposal ini.');
        }

        return view('sertifikat.cetak', compact('proposal'));
    }
}
