@extends('layouts.app')

@section('title', 'Telaah Protokol')

@section('content')
@php 
    // Status telaah spesifik milik penelaah ini
    $isReviewSelesai = ($tugas->status_review === 'selesai');
    
    // Status keseluruhan proposal (5 = Selesai/Lolos)
    $isProposalSelesai = ($tugas->proposal?->status_id == 5);
    
    // Form hanya terkunci otomatis jika proposal sudah selesai
    $isLocked = $isProposalSelesai; 
    
    // Mode view eksplisit (misal dari riwayat)
    $isViewMode = (isset($mode) && $mode === 'view');
    
    // Read-only aktif jika proposal selesai ATAU sedang dalam mode view
    $readOnly = ($isLocked || $isViewMode);
@endphp

<div class="mb-4">
    @if($isViewMode)
        <a href="{{ route('sekretaris.riwayat') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Telaah</a>
    @else
        <a href="{{ route('sekretaris.tugas') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Tugas</a>
    @endif
    
    <h3 class="fw-bold text-dark mb-1">
        {{ $isViewMode ? 'Detail Hasil Telaah Etik' : 'Lembar Kerja Telaah Kelaikan Etik' }}
    </h3>
    <p class="text-muted mb-0">Kode Proposal: <strong>{{ $tugas->proposal?->kode_proposal ?? '-' }}</strong></p>
</div>

@if($isViewMode)
    <div class="alert alert-info border-0 shadow-sm mb-4">
        <i class="bi bi-eye-fill me-2"></i> Mode Tinjauan (Read-Only): Anda sedang melihat riwayat hasil telaah yang telah selesai.
    </div>
@elseif($isLocked)
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        <i class="bi bi-lock-fill me-2"></i> Telaah telah dikunci secara otomatis karena status akhir proposal sudah selesai.
    </div>
@endif

<div class="row">
    <!-- Kolom Kiri: Informasi Penelitian & Berkas Dokumen -->
    <div class="col-lg-6">
        <div class="card card-modern p-4 mb-4">
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i> Informasi Penelitian</h6>
            <table class="table table-borderless small mb-3">
                <tr><th class="text-muted" width="130">Judul Penelitian:</th><td class="fw-bold">{{ $tugas->proposal->judul_penelitian }}</td></tr>
                <tr><th class="text-muted">Peneliti Utama:</th><td>{{ $tugas->proposal->peneliti?->nama_lengkap ?? '-' }}</td></tr>
                <tr><th class="text-muted">Instansi / Kampus:</th><td>{{ $tugas->proposal->peneliti?->profil?->asal_kampus ?? '-' }}</td></tr>
                <tr><th class="text-muted">Tempat Riset:</th><td>{{ $tugas->proposal->tempat_penelitian ?? '-' }}</td></tr>
                <tr><th class="text-muted">Jenis Riset:</th><td>{{ $tugas->proposal->jenisPenelitian?->nama_jenis ?? '-' }}</td></tr>
            </table>

            <hr class="my-3">

            <!-- BAGIAN BERKAS DOKUMEN YANG DIUPLOAD -->
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-folder2-open me-2"></i> Berkas Protokol Peneliti</h6>
            <div class="list-group small mb-3">
                @if(!empty($tugas->proposal?->file_proposal))
                    <a href="{{ asset('uploads/proposal/' . $tugas->proposal->file_proposal) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-file-pdf text-danger me-2"></i> 1. Proposal Penelitian</span>
                        <span class="badge bg-primary rounded-pill">Buka PDF</span>
                    </a>
                @endif
                @if(!empty($tugas->proposal?->file_self_assessment))
                    <a href="{{ asset('uploads/proposal/' . $tugas->proposal->file_self_assessment) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-file-pdf text-danger me-2"></i> 2. Self Assessment Peneliti</span>
                        <span class="badge bg-primary rounded-pill">Buka PDF</span>
                    </a>
                @endif
                @if(!empty($tugas->proposal?->file_kuesioner))
                    <a href="{{ asset('uploads/proposal/' . $tugas->proposal->file_kuesioner) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-file-pdf text-danger me-2"></i> 3. Instrumen / Kuesioner</span>
                        <span class="badge bg-primary rounded-pill">Buka PDF</span>
                    </a>
                @endif
                @if(!empty($tugas->proposal?->file_lampiran))
                    <a href="{{ asset('uploads/proposal/' . $tugas->proposal->file_lampiran) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-file-pdf text-danger me-2"></i> 4. Informed Consent & Lampiran</span>
                        <span class="badge bg-primary rounded-pill">Buka PDF</span>
                    </a>
                @endif
            </div>

            {{-- PENGONDISIAN TOMBOL DAFTAR TILIK --}}
            @php
                $routeTilik = $readOnly ? route('sekretaris.detail-7standar', $tugas->id) : route('sekretaris.form7standar', $tugas->id);
            @endphp

            <a href="{{ $routeTilik }}" class="btn btn-outline-info w-100 py-2 btn-modern">
                <i class="bi bi-ui-checks-grid me-2"></i> {{ $readOnly ? 'Lihat' : 'Buka' }} Daftar Tilik 7 Standar Etik ({{ count($savedAnswers ?? []) }} Butir Terisi)
            </a>
        </div>
    </div>

    <!-- Kolom Kanan: Form Simpan Rekomendasi atau Tampilan Read-Only -->
    <div class="col-lg-6">
        <div class="card card-modern p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-send-check text-primary me-2"></i> Keputusan Rekomendasi Penelaah</h6>
            <form action="{{ route('sekretaris.telaah.simpan', $tugas->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-medium">Rekomendasi Penelaah *</label>
                    <select name="rekomendasi" class="form-select form-control-modern" required {{ $readOnly ? 'disabled' : '' }}>
                        <option value="">-- Pilih Rekomendasi Telaah --</option>
                        <option value="Exempted" {{ $tugas->rekomendasi == 'Exempted' ? 'selected' : '' }}>1. Exempted (Bebas Kaji Etik)</option>
                        <option value="Expedited" {{ $tugas->rekomendasi == 'Expedited' ? 'selected' : '' }}>2. Expedited (Telaah Cepat / Dipercepat)</option>
                        <option value="Full Board" {{ $tugas->rekomendasi == 'Full Board' ? 'selected' : '' }}>3. Full Board (Perlu Sidang Pleno)</option>
                        <option value="Revisi Protokol" {{ $tugas->rekomendasi == 'Revisi Protokol' ? 'selected' : '' }}>4. Perlu Perbaikan / Revisi Protokol</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-medium">Catatan / Telaahan Khusus Penelaah</label>
                    <textarea name="catatan" class="form-control form-control-modern" rows="5" {{ $readOnly ? 'disabled' : '' }} placeholder="Tuliskan alasan pertimbangan, poin-poin saran perbaikan etika riset...">{{ old('catatan', $tugas->catatan) }}</textarea>
                </div>
                
                @if(!$readOnly)
                    <button type="submit" class="btn btn-primary w-100 py-2 btn-modern" style="background: #0d9488; border: none;">
                        <i class="bi bi-check2-circle me-1"></i> {{ $isReviewSelesai ? 'Update Hasil Telaah' : 'Simpan & Serahkan Hasil Telaah' }}
                    </button>
                @else
                    @if($isViewMode)
                        <a href="{{ route('sekretaris.riwayat') }}" class="btn btn-secondary w-100 py-2 btn-modern">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
                        </a>
                    @else
                        <div class="alert alert-success border-0 small text-center mb-0">
                            <i class="bi bi-lock-fill me-1"></i> Proposal telah dinyatakan selesai, keputusan terkunci.
                        </div>
                    @endif
                @endif
            </form>
        </div>
    </div>
</div>
@endsection