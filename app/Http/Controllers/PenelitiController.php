<?php

namespace App\Http\Controllers;

use App\Models\MasterJenisPenelitian;
use App\Models\PengajuanProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PenelitiController extends Controller
{
    public function dashboard()
    {
        $penelitiId = Auth::id();
        $proposals = PengajuanProposal::with(['jenisPenelitian', 'statusProposal', 'sertifikat'])
            ->where('peneliti_id', $penelitiId)
            ->orderBy('id', 'desc')
            ->get();

        $stats = [
            'total' => $proposals->count(),
            'verifikasi' => $proposals->where('status_id', 1)->count(),
            'review' => $proposals->where('status_id', 2)->count(),
            'revisi' => $proposals->where('status_id', 3)->count(),
            'selesai' => $proposals->where('status_id', 5)->count(),
        ];

        return view('peneliti.dashboard', compact('proposals', 'stats'));
    }

    public function create()
    {
        $jenisPenelitian = MasterJenisPenelitian::all();
        return view('peneliti.pengajuan', compact('jenisPenelitian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_penelitian' => 'required|string',
            'tempat_penelitian' => 'required|string',
            'jenis_penelitian_id' => 'required|exists:master_jenis_penelitian,id',
            'file_proposal' => 'required|file|mimes:pdf|max:5120',
            'file_self_assessment' => 'nullable|file|mimes:pdf|max:5120',
            'file_kuesioner' => 'nullable|file|mimes:pdf|max:5120',
            'file_lampiran' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $penelitiId = Auth::id();

        $today = date('Ymd');
        $count = PengajuanProposal::where('kode_proposal', 'like', $today . '%')->count() + 1;
        $kodeProposal = $today . str_pad($count, 4, '0', STR_PAD_LEFT);

        $uploadPath = public_path('uploads/proposal/' . $kodeProposal);
        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true, true);
        }

        $pathProposal = null;
        $pathSelf = null;
        $pathKues = null;
        $pathLamp = null;

        if ($request->hasFile('file_proposal')) {
            $fileName = 'proposal_' . time() . '.pdf';
            $request->file('file_proposal')->move($uploadPath, $fileName);
            $pathProposal = $kodeProposal . '/' . $fileName;
        }

        if ($request->hasFile('file_self_assessment')) {
            $fileName = 'self_assessment_' . time() . '.pdf';
            $request->file('file_self_assessment')->move($uploadPath, $fileName);
            $pathSelf = $kodeProposal . '/' . $fileName;
        }

        if ($request->hasFile('file_kuesioner')) {
            $fileName = 'kuesioner_' . time() . '.pdf';
            $request->file('file_kuesioner')->move($uploadPath, $fileName);
            $pathKues = $kodeProposal . '/' . $fileName;
        }

        if ($request->hasFile('file_lampiran')) {
            $fileName = 'lampiran_' . time() . '.pdf';
            $request->file('file_lampiran')->move($uploadPath, $fileName);
            $pathLamp = $kodeProposal . '/' . $fileName;
        }

        PengajuanProposal::create([
            'kode_proposal' => $kodeProposal,
            'peneliti_id' => $penelitiId,
            'judul_penelitian' => $request->judul_penelitian,
            'tempat_penelitian' => $request->tempat_penelitian,
            'jenis_penelitian_id' => $request->jenis_penelitian_id,
            'status' => 'menunggu_verifikasi_admin',
            'status_id' => 1,
            'tanggal_pengajuan' => date('Y-m-d'),
            'file_proposal' => $pathProposal,
            'file_self_assessment' => $pathSelf,
            'file_kuesioner' => $pathKues,
            'file_lampiran' => $pathLamp,
        ]);

        return redirect()->route('peneliti.status')->with('pesan_sukses', "Proposal berhasil diajukan dengan Kode: <b>$kodeProposal</b>!");
    }

    public function status()
    {
        $penelitiId = Auth::id();
        $proposals = PengajuanProposal::with(['jenisPenelitian', 'statusProposal', 'sertifikat', 'penugasanReview.penelaah.profil'])
            ->where('peneliti_id', $penelitiId)
            ->orderBy('id', 'desc')
            ->get();

        return view('peneliti.status', compact('proposals'));
    }

    public function detail($id)
    {
        $penelitiId = Auth::id();
        $proposal = PengajuanProposal::with(['jenisPenelitian', 'statusProposal', 'sertifikat', 'penugasanReview.penelaah.profil'])
            ->where('peneliti_id', $penelitiId)
            ->where('id', $id)
            ->firstOrFail();

        return view('peneliti.detail', compact('proposal'));
    }

    public function editRevisi($id)
    {
        $penelitiId = Auth::id();
        $proposal = PengajuanProposal::with(['jenisPenelitian', 'statusProposal'])
            ->where('peneliti_id', $penelitiId)
            ->where('id', $id)
            ->firstOrFail();

        return view('peneliti.revisi', compact('proposal'));
    }

    public function updateRevisi(Request $request, $id)
    {
        $penelitiId = Auth::id();
        $proposal = PengajuanProposal::where('peneliti_id', $penelitiId)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'file_proposal' => 'nullable|file|mimes:pdf|max:5120',
            'file_self_assessment' => 'nullable|file|mimes:pdf|max:5120',
            'file_lampiran' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $uploadPath = public_path('uploads/proposal/' . $proposal->kode_proposal);
        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true, true);
        }

        if ($request->hasFile('file_proposal')) {
            $fileName = 'proposal_rev_' . time() . '.pdf';
            $request->file('file_proposal')->move($uploadPath, $fileName);
            $proposal->file_proposal = $proposal->kode_proposal . '/' . $fileName;
        }

        if ($request->hasFile('file_self_assessment')) {
            $fileName = 'self_assessment_rev_' . time() . '.pdf';
            $request->file('file_self_assessment')->move($uploadPath, $fileName);
            $proposal->file_self_assessment = $proposal->kode_proposal . '/' . $fileName;
        }

        if ($request->hasFile('file_lampiran')) {
            $fileName = 'lampiran_rev_' . time() . '.pdf';
            $request->file('file_lampiran')->move($uploadPath, $fileName);
            $proposal->file_lampiran = $proposal->kode_proposal . '/' . $fileName;
        }

        $proposal->status_id = 4;
        $proposal->status = 'sudah_direvisi';
        $proposal->save();

        return redirect()->route('peneliti.status')->with('pesan_sukses', 'Berkas revisi proposal berhasil diunggah!');
    }
}
