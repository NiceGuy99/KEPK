<?php

namespace App\Http\Controllers;

use App\Models\HasilSelfAssessmentPenelaah;
use App\Models\PenugasanReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenelaahController extends Controller
{
    public function dashboard()
    {
        $penelaahId = Auth::id();
        $tugas = PenugasanReview::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian', 'proposal.statusProposal'])
            ->where('penelaah_id', $penelaahId)
            ->orderBy('id', 'desc')
            ->get();

        $stats = [
            'total' => $tugas->count(),
            'menunggu' => $tugas->where('status_review', '!=', 'selesai')->count(),
            'selesai' => $tugas->where('status_review', 'selesai')->count(),
        ];

        return view('penelaah.dashboard', compact('tugas', 'stats'));
    }

    public function tugas()
    {
        $penelaahId = Auth::id();
        
        $tugas = PenugasanReview::with([
                'proposal.peneliti.profil', 
                'proposal.jenisPenelitian', 
                'proposal.statusProposal'
            ])
            ->where('penelaah_id', $penelaahId)
            // Menyembunyikan tugas jika status proposalnya sudah selesai (misal status_id == 5)
            ->whereHas('proposal', function($q) {
                $q->where('status_id', '!=', 5); 
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('penelaah.tugas', compact('tugas'));
    }

    public function telaah($id)
    {
        $penelaahId = Auth::id();
        $penugasan = PenugasanReview::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian', 'proposal.statusProposal'])
            ->where('id', $id)
            ->where('penelaah_id', $penelaahId)
            ->firstOrFail();

        $savedAnswers = HasilSelfAssessmentPenelaah::where('proposal_id', $penugasan->proposal_id)
            ->where('penelaah_id', $penelaahId)
            ->pluck('pilihan', 'indikator_key')
            ->toArray();

        return view('penelaah.telaah', compact('penugasan', 'savedAnswers'));
    }

    public function form7Standar($id)
    {
        $penelaahId = Auth::id();
        $penugasan = PenugasanReview::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian'])
            ->where('id', $id)
            ->where('penelaah_id', $penelaahId)
            ->firstOrFail();

        $savedAnswers = HasilSelfAssessmentPenelaah::where('proposal_id', $penugasan->proposal_id)
            ->where('penelaah_id', $penelaahId)
            ->pluck('pilihan', 'indikator_key')
            ->toArray();

        $isSelesai = ($penugasan->status_review === 'selesai');

        return view('penelaah.form_7standar', compact('penugasan', 'savedAnswers', 'isSelesai'));
    }

    public function simpan7Standar(Request $request, $id)
    {
        $penelaahId = Auth::id();
        $penugasan = PenugasanReview::where('id', $id)
            ->where('penelaah_id', $penelaahId)
            ->firstOrFail();

        // TAMBAHAN: Cegah penyimpanan jika status review sudah selesai
        if ($penugasan->status_review === 'selesai') {
            return redirect()->route('penelaah.form7standar', $id)->with('pesan_error', 'Formulir sudah terkunci dan tidak dapat diubah.');
        }

        $proposalId = $penugasan->proposal_id;

        HasilSelfAssessmentPenelaah::where('proposal_id', $proposalId)
            ->where('penelaah_id', $penelaahId)
            ->delete();

        $keys = [
            'b1', 'b2', 'b3', 'b4a', 'b4b', 'b4c', 'b4d', 'b4e', 'b4f', 'b5', 'b6', 'b7',
            'i1_1', 'i1_1a', 'i1_1b', 'i1_1c', 'i1_2', 'i1_3', 'i1_4', 'i1_5', 'i1_6', 'i1_7',
            'i2_1a', 'i2_1b', 'i2_1c', 'i2_1d', 'i2_1e', 'i2_1f', 'i2_1g', 'i2_1h', 'i2_1i', 'i2_1j', 'i2_1k', 
            // Kunci tambahan Standar 2 (Bahan Biologis & Uji Klinik a-n)
            'i2_bio_a', 'i2_bio_b', 'i2_bio_c', 'i2_bio_d', 'i2_bio_e',
            'i2_2a', 'i2_2b', 
            'i2_Ktr_a', 'i2_Ktr_b', 'i2_Ktr_c', 'i2_Ktr_d', 'i2_Ktr_e', 'i2_Ktr_f', 'i2_Ktr_g', 
            'i2_Ktr_h', 'i2_Ktr_i', 'i2_Ktr_j', 'i2_Ktr_k', 'i2_Ktr_l', 'i2_Ktr_m', 'i2_Ktr_n',
            
            'i3_1', 'i3_2', 'i3_3', 'i3_4', 'i3_5', 'i3_6', 'i3_7', 'i3_8', 'i3_9', 'i3_10', 'i3_11',
            // Kunci tambahan Standar 3 (3.11a sampai k)
            'i3_11a', 'i3_11b', 'i3_11c', 'i3_11d', 'i3_11e', 'i3_11f', 'i3_11g', 'i3_11h', 'i3_11i', 'i3_11j', 'i3_11k',
            
            'i4_1', 'i4_2', 'i4_3', 'i4_4', 'i4_5', 'i4_6', 'i4_7', 'i4_8', 'i4_9', 'i4_10', 'i4_11', 'i4_12', 'i4_13', 'i4_14', 'i4_15',
            'i5_1', 'i5_2', 'i5_3',
            'i6_1', 'i6_2', 'i6_3', 'i6_4', 'i6_a', 'i6_b', 'i6_c', 'i6_d', 'i6_e', 'i6_f', 'i6_g',
            'i7_1', 'i7_2', 'i7_3', 'i7_4', 'i7_5', 'i7_6a', 'i7_6b', 'i7_6c', 'i7_6d', 'i7_6e', 'i7_6f', 'i7_6g', 'i7_6h', 'i7_6i', 'i7_6j', 'i7_6k', 'i7_6l', 'i7_6m', 'i7_6n', 'i7_6o', 'i7_6p', 'i7_6q', 'i7_6r', 'i7_4b'
        ];

        foreach ($keys as $k) {
            if ($request->has($k)) {
                HasilSelfAssessmentPenelaah::create([
                    'proposal_id' => $proposalId,
                    'penelaah_id' => $penelaahId,
                    'indikator_key' => $k,
                    'pilihan' => $request->input($k),
                ]);
            }
        }

        return redirect()->route('penelaah.telaah', $penugasan->id)->with('pesan_sukses', 'Formulir 7 Standar Kelaikan Etik berhasil disimpan!');
    }

    public function simpanTelaah(Request $request, $id)
    {
        $penelaahId = Auth::id();
        $penugasan = PenugasanReview::where('id', $id)
            ->where('penelaah_id', $penelaahId)
            ->firstOrFail();

        // Blokir jika sudah selesai
        if ($penugasan->status_review === 'selesai') {
            return redirect()->route('penelaah.telaah', $id)->with('pesan_error', 'Telaah sudah dikunci dan tidak dapat diubah.');
        }

        $request->validate([
            'rekomendasi' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $penugasan->rekomendasi = $request->rekomendasi;
        $penugasan->catatan = $request->catatan;
        $penugasan->status_review = 'selesai';
        $penugasan->tanggal_review = now();
        $penugasan->save();

        return redirect()->route('penelaah.tugas')->with('pesan_sukses', 'Hasil telaah etik berhasil disimpan dan diserahkan!');
    }

    public function riwayat()
    {
        $penelaahId = Auth::id();
        
        $reviews = PenugasanReview::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian', 'proposal.statusProposal'])
            ->where('penelaah_id', $penelaahId)
            ->where('status_review', 'selesai')
            // Hanya tampilkan di riwayat jika status proposalnya sudah selesai (status_id == 5)
            ->whereHas('proposal', function($q) {
                $q->where('status_id', 5);
            })
            ->orderBy('tanggal_review', 'desc')
            ->get();

        return view('penelaah.riwayat', compact('reviews'));
    }
}
