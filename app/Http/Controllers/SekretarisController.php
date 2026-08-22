<?php

namespace App\Http\Controllers;

use App\Models\HasilSelfAssessmentPenelaah;
use App\Models\MasterJenisPenelitian;
use App\Models\MasterJenisPersetujuan;
use App\Models\MasterRole;
use App\Models\MasterStatusPengguna;
use App\Models\MasterStatusProposal;
use App\Models\PengajuanProposal;
use App\Models\PenugasanReview;
use App\Models\ProfilPengguna;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SekretarisController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_proposal' => PengajuanProposal::count(),
            'menunggu_verifikasi' => PengajuanProposal::where('status_id', 1)->count(),
            'sedang_review' => PengajuanProposal::where('status_id', 2)->count(),
            'revisi' => PengajuanProposal::whereIn('status_id', [3, 4])->count(),
            'selesai' => PengajuanProposal::where('status_id', 5)->count(),
            'total_user' => User::count(),
        ];

        $proposals = PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'statusProposal'])
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('sekretaris.dashboard', compact('stats', 'proposals'));
    }

    public function dataPengguna(Request $request)
    {
        $query = User::with(['role', 'profil.statusPengguna']);

        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                  ->orWhereHas('profil', function ($qp) use ($search) {
                      $qp->where('nama_lengkap', 'like', "%$search%")
                         ->orWhere('nik', 'like', "%$search%")
                         ->orWhere('asal_kampus', 'like', "%$search%");
                  });
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(15);
        $roles = MasterRole::all();
        $statuses = MasterStatusPengguna::all();

        return view('sekretaris.data_pengguna', compact('users', 'roles', 'statuses'));
    }

    public function storePengguna(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'role_id' => 'required|exists:master_role,id',
            'nama_lengkap' => 'required|string',
            'nik' => 'nullable|string',
            'nomor_telepon' => 'nullable|string',
            'asal_kampus' => 'nullable|string',
            'status_id' => 'required|exists:master_status_pengguna,id',
            'is_active' => 'required|boolean',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => $request->is_active ? 1 : 0,
        ]);

        ProfilPengguna::create([
            'user_id' => $user->id,
            'nama_lengkap' => $request->nama_lengkap,
            'nik' => $request->nik,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'asal_kampus' => $request->asal_kampus,
            'status_id' => $request->status_id,
            'alamat' => $request->alamat,
        ]);

        return back()->with('pesan_sukses', 'Pengguna baru berhasil ditambahkan!');
    }

    public function updatePengguna(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:master_role,id',
            'nama_lengkap' => 'required|string',
            'status_id' => 'required|exists:master_status_pengguna,id',
            'is_active' => 'required|boolean',
        ]);

        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->is_active = $request->is_active ? 1 : 0;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $profil = ProfilPengguna::firstOrNew(['user_id' => $user->id]);
        $profil->nama_lengkap = $request->nama_lengkap;
        $profil->nik = $request->nik;
        $profil->email = $request->email;
        $profil->nomor_telepon = $request->nomor_telepon;
        $profil->asal_kampus = $request->asal_kampus;
        $profil->status_id = $request->status_id;
        $profil->alamat = $request->alamat;
        $profil->save();

        return back()->with('pesan_sukses', 'Data pengguna berhasil diperbarui!');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = $user->is_active ? 0 : 1;
        $user->save();

        return back()->with('pesan_sukses', 'Status akun berhasil diubah.');
    }

    public function deletePengguna($id)
    {
        $user = User::findOrFail($id);
        ProfilPengguna::where('user_id', $user->id)->delete();
        $user->delete();

        return back()->with('pesan_sukses', 'Pengguna berhasil dihapus.');
    }

    // --- FUNGSI listPeneliti SUDAH DIPERBARUI DENGAN PENCARIAN ---
    public function listPeneliti(Request $request)
    {
        $query = User::with(['profil.statusPengguna', 'pengajuanProposal'])
            ->where('role_id', 4);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                  ->orWhereHas('profil', function ($qp) use ($search) {
                      $qp->where('nama_lengkap', 'like', "%$search%")
                         ->orWhere('nik', 'like', "%$search%")
                         ->orWhere('asal_kampus', 'like', "%$search%");
                  });
            });
        }

        $peneliti = $query->orderBy('id', 'desc')->get();

        return view('sekretaris.list_peneliti', compact('peneliti'));
    }
    // -------------------------------------------------------------

    public function proposalPeneliti($userId)
    {
        $peneliti = User::with(['profil'])->findOrFail($userId);
        $proposals = PengajuanProposal::with(['jenisPenelitian', 'statusProposal', 'sertifikat'])
            ->where('peneliti_id', $userId)
            ->orderBy('id', 'desc')
            ->get();

        return view('sekretaris.proposal_peneliti', compact('peneliti', 'proposals'));
    }

    public function proposalMasuk(Request $request)
    {
        $query = PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'statusProposal', 'jenisPersetujuan']);

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        $proposals = $query->orderBy('id', 'desc')->paginate(15);
        $masterStatus = MasterStatusProposal::all();

        $calonPenelaah = User::with(['profil', 'role'])
            ->whereIn('role_id', [1, 2, 3])
            ->where('is_active', 1)
            ->get();

        return view('sekretaris.proposal_masuk', compact('proposals', 'masterStatus', 'calonPenelaah'));
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

        $calonPenelaah = User::with('role')->whereIn('role_id', [1, 2, 3])->where('is_active', 1)->get();

        return view('sekretaris.verifikasi', compact('proposal', 'masterStatus', 'masterPersetujuan', 'statusTerkunci', 'calonPenelaah'));
    }

    public function simpanVerifikasi(Request $request, $id)
    {
        $proposal = PengajuanProposal::findOrFail($id);

        $request->validate([
            'keputusan_id' => 'required|exists:master_status_proposal,id',
            'catatan_verifikasi' => 'nullable|string',
            'jenis_persetujuan_id' => 'nullable|exists:master_jenis_persetujuan,id',
        ]);

        $proposal->status_id = $request->keputusan_id;
        $proposal->catatan_verifikasi = $request->catatan_verifikasi;

        if ($request->keputusan_id == 5 && $request->filled('jenis_persetujuan_id')) {
            $proposal->jenis_persetujuan_id = $request->jenis_persetujuan_id;
        }

        $proposal->save();

        return redirect()->route('sekretaris.proposal-masuk')->with('pesan_sukses', 'Keputusan verifikasi berhasil disimpan!');
    }

    public function tugasPenelaahan()
    {
        $sekretarisId = Auth::id(); // Mengambil ID Sekretaris yang sedang login

        $tugas = PenugasanReview::with([
            'proposal.peneliti.profil', 
            'proposal.jenisPenelitian', 
            'proposal.statusProposal',
            'penelaah.profil'
        ])
        ->where('penelaah_id', $sekretarisId) // Hanya menampilkan tugas yang ditugaskan ke sekretaris yang login
        ->whereHas('proposal', function($q) {
            $q->where('status_id', '!=', 5); // Proposal tetap muncul selama statusnya belum selesai (bukan 5)
        })
        ->orderBy('id', 'desc')
        ->get();

        return view('sekretaris.tugas_penelaahan', compact('tugas'));
    }

    public function assignReviewer(Request $request)
    {
        $request->validate([
            'proposal_id' => 'required|exists:pengajuan_proposal,id',
            'penelaah_ids' => 'required|array|min:1|max:5',
            'penelaah_ids.*' => 'exists:users,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        foreach ($request->penelaah_ids as $penelaahId) {
            $exists = PenugasanReview::where('proposal_id', $request->proposal_id)
                ->where('penelaah_id', $penelaahId)
                ->first();

            if (!$exists) {
                PenugasanReview::create([
                    'proposal_id' => $request->proposal_id,
                    'penelaah_id' => $penelaahId,
                    'tanggal_penugasan' => $request->tanggal_mulai,
                    'batas_waktu' => $request->tanggal_selesai,
                    'status_review' => 'menunggu_review',
                ]);
            }
        }

        $proposal = PengajuanProposal::find($request->proposal_id);
        if ($proposal && $proposal->status_id == 1) {
            $proposal->status_id = 2;
            $proposal->status = 'sedang_direview'; 
            $proposal->save();
        }

        return back()->with('pesan_sukses', 'Proposal berhasil diterima dan penelaah telah ditugaskan!');
    }

    public function hapusPenugasan($id)
    {
        $penugasan = PenugasanReview::findOrFail($id);
        $penugasan->delete();

        return back()->with('pesan_sukses', 'Penugasan penelaah berhasil dihapus.');
    }

    public function detailTelaahSekretaris($id)
    {
        $tugas = PenugasanReview::with([
            'proposal.peneliti.profil', 
            'proposal.jenisPenelitian',
            'proposal.statusProposal',
            'penelaah.profil'
        ])->findOrFail($id);

        $savedAnswers = HasilSelfAssessmentPenelaah::where('proposal_id', $tugas->proposal_id)
            ->where('penelaah_id', $tugas->penelaah_id)
            ->pluck('pilihan', 'indikator_key')
            ->toArray();

        $mode = 'view'; // Menandakan halaman ini adalah mode lihat (bukan edit)

        // Menggunakan view sekretaris.telaah namun dengan mode 'view'
        return view('sekretaris.telaah', compact('tugas', 'savedAnswers', 'mode'));
    }

    // Fungsi saat membuka formulir untuk mengisi (Edit Mode)
    public function form7StandarSekretaris($id)
    {
        $sekretarisId = Auth::id();
        // Tambahkan with('proposal') untuk mengecek status proposal
        $penugasan = PenugasanReview::with(['proposal.peneliti.profil', 'proposal.jenisPenelitian', 'penelaah.profil'])
            ->where('id', $id)
            ->where('penelaah_id', $sekretarisId)
            ->firstOrFail();

        $savedAnswers = HasilSelfAssessmentPenelaah::where('proposal_id', $penugasan->proposal_id)
            ->where('penelaah_id', $sekretarisId)
            ->pluck('pilihan', 'indikator_key')
            ->toArray();

        // Cek apakah proposal sudah selesai (misalnya status_id == 5)
        $isProposalSelesai = ($penugasan->proposal->status_id == 5);
        $isSelesai = ($penugasan->status_review === 'selesai' || $isProposalSelesai);
        
        // Jika proposal sudah selesai, paksa mode ke 'view'
        $mode = $isProposalSelesai ? 'view' : 'edit';
        
        return view('sekretaris.form_7standar', compact('penugasan', 'savedAnswers', 'isSelesai', 'mode'));
    }

    // Fungsi saat membuka dari Detail Telaah (View Only Mode)
    public function detail7StandarSekretaris($id)
    {
        $penugasan = PenugasanReview::findOrFail($id);
        $savedAnswers = HasilSelfAssessmentPenelaah::where('proposal_id', $penugasan->proposal_id)->where('penelaah_id', $penugasan->penelaah_id)->pluck('pilihan', 'indikator_key')->toArray();
        
        $isSelesai = true; // Paksa terkunci
        $mode = 'view'; // Mode hanya lihat
        
        return view('sekretaris.form_7standar', compact('penugasan', 'savedAnswers', 'isSelesai', 'mode'));
    }

    public function simpan7StandarSekretaris(Request $request, $id)
    {
        $sekretarisId = Auth::id();
        $penugasan = PenugasanReview::where('id', $id)
            ->where('penelaah_id', $sekretarisId)
            ->firstOrFail();

        if ($penugasan->status_review === 'selesai') {
            return redirect()->route('sekretaris.form7standar', $id)->with('pesan_error', 'Formulir sudah terkunci.');
        }

        $proposalId = $penugasan->proposal_id;

        HasilSelfAssessmentPenelaah::where('proposal_id', $proposalId)
            ->where('penelaah_id', $sekretarisId)
            ->delete();

        $keys = [
            'b1', 'b2', 'b3', 'b4a', 'b4b', 'b4c', 'b4d', 'b4e', 'b4f', 'b5', 'b6', 'b7',
            'i1_1', 'i1_1a', 'i1_1b', 'i1_1c', 'i1_2', 'i1_3', 'i1_4', 'i1_5', 'i1_6', 'i1_7',
            'i2_1a', 'i2_1b', 'i2_1c', 'i2_1d', 'i2_1e', 'i2_1f', 'i2_1g', 'i2_1h', 'i2_1i', 'i2_1j', 'i2_1k', 
            'i2_bio_a', 'i2_bio_b', 'i2_bio_c', 'i2_bio_d', 'i2_bio_e',
            'i2_2a', 'i2_2b', 
            'i2_Ktr_a', 'i2_Ktr_b', 'i2_Ktr_c', 'i2_Ktr_d', 'i2_Ktr_e', 'i2_Ktr_f', 'i2_Ktr_g', 
            'i2_Ktr_h', 'i2_Ktr_i', 'i2_Ktr_j', 'i2_Ktr_k', 'i2_Ktr_l', 'i2_Ktr_m', 'i2_Ktr_n',
            'i3_1', 'i3_2', 'i3_3', 'i3_4', 'i3_5', 'i3_6', 'i3_7', 'i3_8', 'i3_9', 'i3_10', 'i3_11',
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
                    'penelaah_id' => $sekretarisId,
                    'indikator_key' => $k,
                    'pilihan' => $request->input($k),
                ]);
            }
        }

        return redirect()->route('sekretaris.telaah', $penugasan->id)->with('pesan_sukses', 'Formulir 7 Standar berhasil disimpan!');
    }

    public function simpanTelaah(Request $request, $id)
    {
        $sekretarisId = Auth::id();
        // Tambahkan with('proposal') agar bisa mengecek status proposalnya
        $penugasan = PenugasanReview::with('proposal')
            ->where('id', $id)
            ->where('penelaah_id', $sekretarisId)
            ->firstOrFail();

        // UBAH LOGIKA BLOKIR: Hanya tolak jika status proposal sudah 5 (Selesai)
        if ($penugasan->proposal->status_id == 5) {
            return redirect()->route('sekretaris.telaah', $id)->with('pesan_error', 'Proposal sudah selesai, telaah tidak dapat diubah.');
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

        return redirect()->route('sekretaris.tugas')->with('pesan_sukses', 'Hasil telaah etik berhasil disimpan dan diperbarui!');
    }

    public function riwayatTelaah()
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

    public function telaah($id)
    {
        $tugas = PenugasanReview::with([
            'proposal.peneliti.profil', 
            'proposal.jenisPenelitian', 
            'proposal.statusProposal',
            'penelaah.profil'
        ])->findOrFail($id);

        $savedAnswers = HasilSelfAssessmentPenelaah::where('proposal_id', $tugas->proposal_id)
            ->where('penelaah_id', $tugas->penelaah_id)
            ->pluck('pilihan', 'indikator_key')
            ->toArray();

        // Cek apakah proposal sudah selesai (status_id == 5) atau review sudah selesai
        $isProposalSelesai = ($tugas->proposal->status_id == 5);
        $isSelesai = ($tugas->status_review === 'selesai' || $isProposalSelesai);
        
        // Jika proposal sudah selesai, set mode ke 'view'
        $mode = $isProposalSelesai ? 'view' : 'edit';

        return view('sekretaris.telaah', compact('tugas', 'savedAnswers', 'mode'));
    }

    public function detailHasilTelaah($proposalId, $penelaahId)
    {
        $proposal = PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian'])->findOrFail($proposalId);
        $penelaah = User::with('profil')->findOrFail($penelaahId);
        $penugasan = PenugasanReview::where('proposal_id', $proposalId)
            ->where('penelaah_id', $penelaahId)
            ->firstOrFail();

        $savedAnswers = HasilSelfAssessmentPenelaah::where('proposal_id', $proposalId)
            ->where('penelaah_id', $penelaahId)
            ->pluck('pilihan', 'indikator_key')
            ->toArray();

        return view('sekretaris.detail_hasil_telaah', compact('proposal', 'penelaah', 'penugasan', 'savedAnswers'));
    }

    public function daftarSertifikat()
    {
        $proposalsSelesai = PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'sertifikat'])
            ->where('status_id', 5)
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('sekretaris.daftar_sertifikat', compact('proposalsSelesai'));
    }

    // --- TAMBAHAN BARU UNTUK FORM & SIMPAN SERTIFIKAT ---
    public function formSertifikat($id)
    {
        $proposal = PengajuanProposal::with(['peneliti.profil'])->findOrFail($id);
        
        // Sesuaikan path folder view ke folder 'sertifikat'
        return view('sertifikat.form_sertifikat', compact('proposal'));
    }

    public function simpanSertifikat(Request $request, $id)
    {
        $request->validate([
            'tanggal_terbit' => 'required|date',
        ]);

        // 1. Simpan/Update data dasar sertifikat untuk mendapatkan ID
        $sertifikat = Sertifikat::updateOrCreate(
            ['proposal_id' => $id],
            [
                'tanggal_terbit' => $request->tanggal_terbit,
                'nama_pejabat' => 'Dr. dr. Arif Rahman Nurdianto, M.Imun., S.H., M.H.', // Di-set otomatis secara tetap
                'nomor_sertifikat' => 'TEMP', 
            ]
        );

        // 2. Format nomor urut 5 digit dari ID sertifikat
        $nomorUrut = str_pad($sertifikat->id, 5, '0', STR_PAD_LEFT);
        
        // 3. Ambil bulan dan tahun dari tanggal terbit
        $bulan = date('m', strtotime($request->tanggal_terbit));
        $tahun = date('Y', strtotime($request->tanggal_terbit));

        // 4. Susun format akhir: [Nomor Urut]/KEPKRSUDSIBAR/[Bulan]/[Tahun]
        $nomorSertifikatFinal = "{$nomorUrut}/KEPKRSUDSIBAR/{$bulan}/{$tahun}";

        // 5. Update nomor sertifikat final ke database
        $sertifikat->nomor_sertifikat = $nomorSertifikatFinal;
        $sertifikat->save();

        return redirect()->route('sertifikat.cetak', $id)->with('pesan_sukses', 'Sertifikat berhasil diterbitkan!');
    }
    // ----------------------------------------------------

    public function gantiPenelaah(Request $request, $id)
    {
        $penugasan = PenugasanReview::findOrFail($id);
        
        $request->validate([
            'penelaah_baru_id' => 'required|exists:users,id',
        ]);

        $exists = PenugasanReview::where('proposal_id', $penugasan->proposal_id)
            ->where('penelaah_id', $request->penelaah_baru_id)
            ->first();

        if ($exists) {
            return back()->with('pesan_error', 'Gagal mengganti. Penelaah tersebut sudah ditugaskan pada proposal ini.');
        }

        $penugasan->penelaah_id = $request->penelaah_baru_id;
        $penugasan->save();

        return back()->with('pesan_sukses', 'Penelaah berhasil diganti!');
    }

    public function proposalBelumSelesai(Request $request)
    {
        // 1. Mengambil SEMUA proposal tanpa kecuali (tidak ada where status_id != 5)
        $query = PengajuanProposal::with(['peneliti.profil', 'jenisPenelitian', 'statusProposal', 'jenisPersetujuan']);

        // 2. Filter dari dropdown (jika Ketua memilih status tertentu untuk dicari)
        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        // 3. Menampilkan semua proposal tapi tetap dipotong 15 baris per halaman agar rapi
        $proposals = $query->orderBy('id', 'desc')->paginate(15);
        
        // 4. Ambil semua opsi master status untuk dropdown (termasuk status Selesai)
        $masterStatus = MasterStatusProposal::all();

        // 5. Kirim ke view
        return view('sekretaris.proposal_belum_selesai', compact('proposals', 'masterStatus'));
    }

    // Menampilkan halaman Matriks Hak Akses
    public function hakAkses()
    {
        // 1. Ambil data role beserta relasi menunya
        $roles = MasterRole::with('menus')->get();
        
        // 2. Ambil semua master menu
        $menus = \App\Models\MasterMenu::orderBy('urutan', 'asc')->get();

        // 3. Kelompokkan menu berdasarkan kategori. 
        // (Jika tabel master_menus belum punya kolom 'kategori', otomatis dikelompokkan ke 'Utama')
        $menusByKategori = $menus->groupBy(function($menu) {
            return $menu->kategori ?? 'Menu Navigasi Utama';
        });

        return view('sekretaris.hak_akses', compact('roles', 'menus', 'menusByKategori'));
    }

    // Menyimpan perubahan Matriks Hak Akses
    public function updateHakAkses(Request $request)
    {
        // Ambil semua data role
        $roles = MasterRole::all();
        
        // Ambil payload dari form (array permissions)
        // Jika tidak ada yang dicentang sama sekali, defaultnya array kosong
        $permissions = $request->input('permissions', []);

        // Looping setiap role untuk memperbarui relasi menunya
        foreach ($roles as $role) {
            // Jika role ini memiliki menu yang dicentang di form
            if (isset($permissions[$role->id])) {
                // Ambil semua ID menu (kunci dari array)
                $menuIds = array_keys($permissions[$role->id]);
                $role->menus()->sync($menuIds);
            } else {
                // Jika tidak ada yang dicentang satupun untuk role ini, hapus semua aksesnya
                $role->menus()->sync([]);
            }
        }

        return back()->with('pesan_sukses', 'Matriks Hak Akses berhasil diperbarui untuk semua Role!');
    }
}