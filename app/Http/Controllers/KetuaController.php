<?php

namespace App\Http\Controllers;

use App\Models\MasterJenisPenelitian;
use App\Models\MasterJenisPersetujuan;
use App\Models\MasterStatusProposal;
use App\Models\PengajuanProposal;
use App\Models\PenugasanReview;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Http\Request;

class KetuaController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_proposal' => PengajuanProposal::count(),
            'menunggu_verifikasi' => PengajuanProposal::where('status_id', 1)->count(),
            'sedang_review' => PengajuanProposal::where('status_id', 2)->count(),
            'revisi' => PengajuanProposal::whereIn('status_id', [3, 4])->count(),
            'selesai' => PengajuanProposal::where('status_id', 5)->count(),
            'total_sertifikat' => Sertifikat::count(),
            'total_user' => User::count(),
        ];

        // Monthly statistics for current year
        $currentYear = date('Y');
        $monthlyCounts = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyCounts[] = PengajuanProposal::where(function($q) use ($currentYear, $m) {
                $q->where(function($sq) use ($currentYear, $m) {
                    $sq->whereYear('tanggal_pengajuan', $currentYear)
                       ->whereMonth('tanggal_pengajuan', $m);
                })->orWhere(function($sq) use ($currentYear, $m) {
                    $sq->whereNull('tanggal_pengajuan')
                       ->whereYear('created_at', $currentYear)
                       ->whereMonth('created_at', $m);
                });
            })->count();
        }

        $proposals = PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'statusProposal', 'sertifikat'])
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('ketua.dashboard', compact('stats', 'proposals', 'monthlyCounts'));
    }

    public function listPeneliti()
    {
        $peneliti = User::with(['profil.statusPengguna', 'pengajuanProposal'])
            ->where('role_id', 4)
            ->orderBy('id', 'desc')
            ->get();

        return view('ketua.list_peneliti', compact('peneliti'));
    }

    public function proposalPeneliti($userId)
    {
        $peneliti = User::with('profil')->findOrFail($userId);
        $proposals = PengajuanProposal::with(['jenisPenelitian', 'statusProposal', 'sertifikat'])
            ->where('peneliti_id', $userId)
            ->orderBy('id', 'desc')
            ->get();

        return view('ketua.proposal_peneliti', compact('peneliti', 'proposals'));
    }

    public function proposalMasuk(Request $request)
    {
        $query = PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'statusProposal', 'jenisPersetujuan', 'penugasanReview']);

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_penelitian', 'like', "%$search%")
                  ->orWhere('kode_proposal', 'like', "%$search%")
                  ->orWhereHas('peneliti.profil', function ($qp) use ($search) {
                      $qp->where('nama_lengkap', 'like', "%$search%")
                         ->orWhere('asal_kampus', 'like', "%$search%");
                  });
            });
        }

        $proposals = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        $masterStatus = MasterStatusProposal::all();

        return view('ketua.proposal_masuk', compact('proposals', 'masterStatus'));
    }

    public function verifikasiBerkas($id)
    {
        $proposal = PengajuanProposal::with([
            'peneliti.profil.statusPengguna',
            'jenisPenelitian',
            'statusProposal',
            'jenisPersetujuan',
            'penugasanReview.penelaah.profil',
        ])->findOrFail($id);

        $masterStatus = MasterStatusProposal::where('id', '>=', 3)->orderBy('id', 'asc')->get();
        $masterPersetujuan = MasterJenisPersetujuan::all();
        $statusTerkunci = in_array($proposal->status_id, [3, 5]);

        return view('sekretaris.verifikasi', compact('proposal', 'masterStatus', 'masterPersetujuan', 'statusTerkunci'));
    }

    public function simpanVerifikasi(Request $request, $id)
    {
        return app(SekretarisController::class)->simpanVerifikasi($request, $id);
    }

    public function tugasPenelaahan()
    {
        $proposals = PengajuanProposal::with([
            'peneliti.profil',
            'jenisPenelitian',
            'statusProposal',
            'penugasanReview.penelaah.profil',
        ])->whereIn('status_id', [1, 2, 4])
          ->orderBy('id', 'desc')
          ->get();

        return view('ketua.tugas_penelaahan', compact('proposals'));
    }

    public function riwayatTelaah()
    {
        $reviews = PenugasanReview::with(['proposal.peneliti.profil', 'penelaah.profil'])
            ->where('status_review', 'selesai')
            ->orderBy('tanggal_review', 'desc')
            ->paginate(15);

        return view('ketua.riwayat_telaah', compact('reviews'));
    }

    public function formSertifikat($proposalId)
    {
        $proposal = PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'sertifikat'])->findOrFail($proposalId);
        return view('ketua.form_sertifikat', compact('proposal'));
    }

    public function simpanSertifikat(Request $request, $proposalId)
    {
        $proposal = PengajuanProposal::findOrFail($proposalId);

        $request->validate([
            'nomor_sertifikat' => 'required|string',
            'tanggal_terbit' => 'required|date',
            'nama_pejabat' => 'required|string',
        ]);

        $sertifikat = Sertifikat::firstOrNew(['proposal_id' => $proposal->id]);
        $sertifikat->nomor_sertifikat = $request->nomor_sertifikat;
        $sertifikat->tanggal_terbit = $request->tanggal_terbit;
        $sertifikat->nama_pejabat = $request->nama_pejabat;
        $sertifikat->save();

        $proposal->status_id = 5;
        $proposal->status = 'selesai';
        $proposal->save();

        // Notifikasi WA Otomatis ke Peneliti
        try {
            $proposal->load('peneliti.profil');
            if ($proposal->peneliti) {
                app(\App\Services\WhatsAppService::class)->notifSertifikatTerbit(
                    $proposal->peneliti,
                    $proposal,
                    $request->nomor_sertifikat
                );
            }
        } catch (\Throwable $e) {}

        return redirect()->route('sertifikat.cetak', $proposal->id)->with('pesan_sukses', 'Sertifikat kelaikan etik berhasil diterbitkan!');
    }

    public function daftarSertifikat(Request $request)
    {
        $query = Sertifikat::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sertifikat', 'like', "%$search%")
                  ->orWhereHas('proposal', function ($qp) use ($search) {
                      $qp->where('judul_penelitian', 'like', "%$search%")
                         ->orWhere('kode_proposal', 'like', "%$search%")
                         ->orWhereHas('peneliti.profil', function ($qpp) use ($search) {
                             $qpp->where('nama_lengkap', 'like', "%$search%")
                                ->orWhere('asal_kampus', 'like', "%$search%");
                         });
                  });
            });
        }

        $sertifikat = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('ketua.daftar_sertifikat', compact('sertifikat'));
    }

    public function proposalBelumSelesai(Request $request)
    {
        // Mengambil semua proposal yang statusnya BUKAN selesai (bukan 5)
        $query = PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'statusProposal', 'jenisPersetujuan'])
            ->where('status_id', '!=', 5);

        // Fitur filter berdasarkan status jika Ketua memilih dari dropdown
        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        $proposals = $query->orderBy('id', 'desc')->paginate(15);
        
        // Ambil master status (selain selesai) untuk opsi dropdown filter
        $masterStatus = MasterStatusProposal::where('id', '!=', 5)->get();

        // Kita gunakan view milik sekretaris agar tidak perlu membuat file view terpisah
        return view('sekretaris.proposal_belum_selesai', compact('proposals', 'masterStatus'));
    }

    public function detailTelaahSekretaris($id)
    {
        return app(\App\Http\Controllers\SekretarisController::class)->detailTelaahSekretaris($id);
    }
}

