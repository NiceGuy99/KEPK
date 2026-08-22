<?php

namespace App\Http\Controllers;

use App\Models\PengajuanProposal;
use App\Models\PenugasanReview;
use App\Models\Sertifikat;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tglAwal = $request->input('tgl_awal', date('Y-m-01'));
        $tglAkhir = $request->input('tgl_akhir', date('Y-m-d'));
        $jenis = $request->input('jenis', 'pengajuan');

        $data = match ($jenis) {
            'pengajuan' => PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian'])
                ->whereBetween('tanggal_pengajuan', [$tglAwal, $tglAkhir])
                ->orderBy('id', 'desc')
                ->paginate(15),
            'telaah' => PenugasanReview::with(['proposal.peneliti.profil', 'penelaah.profil'])
                ->whereBetween('tanggal_penugasan', [$tglAwal, $tglAkhir])
                ->orderBy('id', 'desc')
                ->paginate(15),
            'selesai' => PengajuanProposal::with(['peneliti.profil', 'jenisPersetujuan'])
                ->where('status_id', 5)
                ->whereBetween('tanggal_pengajuan', [$tglAwal, $tglAkhir])
                ->orderBy('id', 'desc')
                ->paginate(15),
            'sertifikat' => Sertifikat::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian'])
                ->whereBetween('tanggal_terbit', [$tglAwal, $tglAkhir])
                ->orderBy('id', 'desc')
                ->paginate(15),
            default => collect(),
        };

        return view('laporan.index', compact('data', 'tglAwal', 'tglAkhir', 'jenis'));
    }

    public function cetak(Request $request)
    {
        $tglAwal = $request->input('tgl_awal', date('Y-m-01'));
        $tglAkhir = $request->input('tgl_akhir', date('Y-m-d'));
        $jenis = $request->input('jenis', 'pengajuan');

        $data = match ($jenis) {
            'pengajuan' => PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian'])
                ->whereBetween('tanggal_pengajuan', [$tglAwal, $tglAkhir])
                ->orderBy('id', 'desc')
                ->get(),
            'telaah' => PenugasanReview::with(['proposal.peneliti.profil', 'penelaah.profil'])
                ->whereBetween('tanggal_penugasan', [$tglAwal, $tglAkhir])
                ->orderBy('id', 'desc')
                ->get(),
            'selesai' => PengajuanProposal::with(['peneliti.profil', 'jenisPersetujuan'])
                ->where('status_id', 5)
                ->whereBetween('tanggal_pengajuan', [$tglAwal, $tglAkhir])
                ->orderBy('id', 'desc')
                ->get(),
            'sertifikat' => Sertifikat::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian'])
                ->whereBetween('tanggal_terbit', [$tglAwal, $tglAkhir])
                ->orderBy('id', 'desc')
                ->get(),
            default => collect(),
        };

        return view('laporan.cetak', compact('data', 'tglAwal', 'tglAkhir', 'jenis'));
    }
}
