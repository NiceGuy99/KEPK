@extends('layouts.app')

@section('title', 'Verifikasi Berkas Proposal')

@section('content')
<div class="mb-4">
    <a href="javascript:history.back()" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    <h3 class="fw-bold text-dark">Verifikasi Berkas Proposal</h3>
    <p class="text-muted mb-0">Kode Proposal: <strong>{{ $proposal->kode_proposal ?? '-' }}</strong></p>
</div>

<!-- Notifikasi Pesan Sukses / Error -->
@if(session('pesan_sukses'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('pesan_sukses') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('pesan_error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('pesan_error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Kolom Kiri: Informasi Peneliti, Berkas, & Tinjauan Penelaah -->
    <div class="col-md-5">
        <div class="card card-modern p-4 mb-4">
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i> Informasi Peneliti</h6>
            <p class="mb-1"><strong>Nama:</strong> {{ $proposal->peneliti?->nama_lengkap ?? '-' }}</p>
            <p class="mb-1"><strong>Asal Kampus:</strong> {{ $proposal->peneliti?->profil?->asal_kampus ?? '-' }}</p>
            <p class="mb-1"><strong>Telepon:</strong> {{ $proposal->peneliti?->profil?->nomor_telepon ?? '-' }}</p>
            <p class="mb-1"><strong>Jenis Penelitian:</strong> {{ $proposal->jenisPenelitian?->nama_jenis ?? '-' }}</p>
            <p class="mb-1"><strong>Tempat Penelitian:</strong> {{ $proposal->tempat_penelitian ?? '-' }}</p>
            
            <hr class="my-3">

            <!-- BERKAS PENGAJUAN LENGKAP -->
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-file-earmark-text me-2"></i> Berkas Pengajuan (Lengkap)</h6>
            <div class="list-group mb-3 small">
                @if(!empty($proposal->file_proposal))
                    <a href="{{ asset('uploads/proposal/' . $proposal->file_proposal) }}" target="_blank" class="list-group-item list-group-item-action py-2">
                        <i class="bi bi-file-pdf text-danger me-2"></i> Proposal Utama
                    </a>
                @endif

                @if(!empty($proposal->file_self_assessment))
                    <a href="{{ asset('uploads/proposal/' . $proposal->file_self_assessment) }}" target="_blank" class="list-group-item list-group-item-action py-2">
                        <i class="bi bi-file-pdf text-danger me-2"></i> Self Assessment
                    </a>
                @endif

                @if(!empty($proposal->file_kuesioner))
                    <a href="{{ asset('uploads/proposal/' . $proposal->file_kuesioner) }}" target="_blank" class="list-group-item list-group-item-action py-2">
                        <i class="bi bi-file-pdf text-danger me-2"></i> Contoh Kuesioner
                    </a>
                @endif

                @if(!empty($proposal->file_lampiran))
                    <a href="{{ asset('uploads/proposal/' . $proposal->file_lampiran) }}" target="_blank" class="list-group-item list-group-item-action py-2">
                        <i class="bi bi-file-pdf text-danger me-2"></i> Lampiran Lainnya
                    </a>
                @endif

                @if(empty($proposal->file_proposal) && empty($proposal->file_self_assessment) && empty($proposal->file_kuesioner) && empty($proposal->file_lampiran))
                    <span class="text-muted italic">Tidak ada berkas yang diunggah.</span>
                @endif
            </div>

            <!-- Tinjauan Penelaah Etik -->
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-people me-2"></i> Tinjauan Penelaah Etik</h6>
            @if($proposal->penugasanReview->count() > 0)
                <div class="list-group">
                    @foreach($proposal->penugasanReview as $tugas)
                        <div class="list-group-item py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="fw-bold d-block">{{ $tugas->penelaah?->profil?->nama_lengkap ?? $tugas->penelaah?->email }}</small>
                                <span class="badge bg-light text-dark border">Rekomendasi: {{ $tugas->rekomendasi ?? 'Belum ada' }}</span>
                            </div>
                            <!-- Tombol Detail Tilik menuju lembar telaah read-only sekretaris -->
                            <a href="{{ route('sekretaris.detail-telaah', $tugas->id) }}" class="btn btn-sm btn-outline-info">Detail Tilik</a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="small text-muted italic">Belum ada penelaah yang ditugaskan.</p>
            @endif
        </div>
    </div>

    <!-- Kolom Kanan: Form Keputusan Verifikasi -->
    <div class="col-md-7">
        <div class="card card-modern p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-shield-check text-primary me-2"></i> Keputusan Verifikasi Berkas</h5>

            @if($statusTerkunci)
                <div class="alert alert-warning border-0 shadow-sm mb-4">
                    <i class="bi bi-lock-fill me-2"></i> <b>Berkas Terkunci:</b> Status saat ini adalah <b>{{ $proposal->statusProposal?->nama_status }}</b>. Anda hanya dapat melihat data dan hasil verifikasi tanpa bisa mengubahnya.
                </div>
            @else
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <i class="bi bi-unlock-fill me-2"></i> <b>Status Aktif:</b> {{ $proposal->statusProposal?->nama_status }}. Anda masih dapat mengubah keputusan atau catatan verifikasi.
                </div>
            @endif

            @php
                $actionRoute = (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->role?->nama_role == 'ketua'))
                    ? route('ketua.verifikasi.simpan', $proposal->id)
                    : route('sekretaris.verifikasi.simpan', $proposal->id);
            @endphp
            <form action="{{ $actionRoute }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-medium">Ubah Keputusan Status</label>
                    <select name="keputusan_id" id="keputusan_id" class="form-select form-control-modern" required onchange="toggleJenisPersetujuan()" {{ $statusTerkunci ? 'disabled' : '' }}>
                        <option value="">-- Pilih Status --</option>
                        @foreach($masterStatus as $st)
                            <option value="{{ $st->id }}" {{ $proposal->status_id == $st->id ? 'selected' : '' }}>{{ $st->nama_status }}</option>
                        @endforeach
                    </select>
                    @if($statusTerkunci)
                        <input type="hidden" name="keputusan_id" value="{{ $proposal->status_id }}">
                    @endif
                </div>

                <!-- Pilihan Jenis Persetujuan (Hanya muncul jika Status dipilih SELESAI / ID 5) -->
                <div class="mb-3" id="wrapper_jenis_persetujuan" style="display: {{ ($proposal->status_id == 5) ? 'block' : 'none' }};">
                    <label class="form-label fw-medium text-success"><i class="bi bi-award me-1"></i> Jenis Persetujuan Etik <span class="text-danger">*</span></label>
                    <select name="jenis_persetujuan_id" class="form-select form-control-modern border-success" {{ $statusTerkunci ? 'disabled' : '' }}>
                        <option value="">-- Pilih Jenis Persetujuan --</option>
                        @foreach($masterPersetujuan as $jp)
                            <option value="{{ $jp->id }}" {{ $proposal->jenis_persetujuan_id == $jp->id ? 'selected' : '' }}>{{ $jp->nama_persetujuan }}</option>
                        @endforeach
                    </select>
                    @if($statusTerkunci && $proposal->status_id == 5)
                        <input type="hidden" name="jenis_persetujuan_id" value="{{ $proposal->jenis_persetujuan_id }}">
                    @endif
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Catatan Verifikasi</label>
                    <textarea name="catatan_verifikasi" class="form-control form-control-modern" rows="5" placeholder="Tuliskan catatan atau kekurangan berkas di sini..." {{ $statusTerkunci ? 'readonly' : '' }}>{{ old('catatan_verifikasi', $proposal->catatan_verifikasi) }}</textarea>
                </div>

                @if(!$statusTerkunci)
                    <button type="submit" class="btn btn-primary btn-modern w-100 shadow-sm py-2" style="background: #0d9488; border: none;">
                        Simpan Keputusan Verifikasi <i class="bi bi-save ms-1"></i>
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- Script JavaScript untuk memunculkan pilihan Jenis Persetujuan saat status Selesai (ID 5) dipilih -->
<script>
function toggleJenisPersetujuan() {
    var statusSelect = document.getElementById('keputusan_id');
    var wrapperJP = document.getElementById('wrapper_jenis_persetujuan');
    
    if (statusSelect.value == '5') {
        wrapperJP.style.display = 'block';
    } else {
        wrapperJP.style.display = 'none';
    }
}
</script>
@endsection