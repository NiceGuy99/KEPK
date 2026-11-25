@extends('layouts.app')

@section('title', 'Tinjauan Lembar Kerja Telaah')

@section('content')
@php
    $isKetua = (auth()->check() && (auth()->user()->role_id == 2 || auth()->user()->role?->nama_role == 'ketua'));
    $backUrl = $isKetua ? route('ketua.riwayat') : route('sekretaris.riwayat');
    $form7Url = $isKetua ? route('ketua.detail-7standar', $tugas->id) : route('sekretaris.form7standar', $tugas->id);
@endphp

<div class="mb-4">
    <a href="{{ $backUrl }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Telaah
    </a>
    <h3 class="fw-bold text-dark mb-1">Lembar Kerja Telaah Kelaikan Etik (Tinjauan {{ $isKetua ? 'Ketua' : 'Sekretaris' }})</h3>
    <p class="text-muted mb-0">Kode Proposal: <strong>{{ $proposal->kode_proposal ?? '-' }}</strong> | Penelaah: <strong>{{ $tugas->penelaah?->profil?->nama_lengkap ?? $tugas->penelaah?->email }}</strong></p>
</div>

<div class="row">
    <!-- KOLOM KIRI: Informasi & Berkas -->
    <div class="col-lg-7">
        <div class="card card-modern p-4 mb-4">
            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i> Informasi Penelitian</h5>
            <table class="table table-borderless small mb-0">
                <tr><th width="150" class="text-muted">Judul Penelitian:</th><td class="fw-bold text-dark">{{ $proposal->judul_penelitian }}</td></tr>
                <tr><th class="text-muted">Peneliti Utama:</th><td>{{ $proposal->peneliti?->nama_lengkap ?? '-' }}</td></tr>
                <tr><th class="text-muted">Instansi / Kampus:</th><td>{{ $proposal->peneliti?->profil?->asal_kampus ?? '-' }}</td></tr>
                <tr><th class="text-muted">Tempat Riset:</th><td>{{ $proposal->tempat_penelitian ?? '-' }}</td></tr>
                <tr><th class="text-muted">Jenis Riset:</th><td>{{ $proposal->jenisPenelitian?->nama_jenis ?? '-' }}</td></tr>
            </table>

            <hr class="my-3">

            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-folder2-open me-2"></i> Berkas Protokol Peneliti</h5>
            <div class="list-group small mb-3">
                @if(!empty($proposal->file_proposal))
                    <a href="{{ asset('uploads/proposal/' . $proposal->file_proposal) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-file-pdf text-danger me-2"></i> 1. Proposal Penelitian</span>
                        <span class="badge bg-primary rounded-pill">Buka PDF</span>
                    </a>
                @endif
                @if(!empty($proposal->file_self_assessment))
                    <a href="{{ asset('uploads/proposal/' . $proposal->file_self_assessment) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-file-pdf text-danger me-2"></i> 2. Self Assessment</span>
                        <span class="badge bg-primary rounded-pill">Buka PDF</span>
                    </a>
                @endif
                @if(!empty($proposal->file_kuesioner))
                    <a href="{{ asset('uploads/proposal/' . $proposal->file_kuesioner) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-file-pdf text-danger me-2"></i> 3. Kuesioner / Instrumen</span>
                        <span class="badge bg-primary rounded-pill">Buka PDF</span>
                    </a>
                @endif
            </div>

            <!-- Tombol ke Form 7 Standar -->
            <a href="{{ $form7Url }}" class="btn btn-outline-primary btn-modern w-100 py-2">
                <i class="bi bi-list-check me-1"></i> Buka Formulir Daftar Tilik 7 Standar Etik (Tinjauan)
            </a>
        </div>
    </div>

    <!-- KOLOM KANAN: Rekomendasi Penelaah -->
    <div class="col-lg-5">
        <div class="card card-modern p-4 mb-4 border-top border-success border-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clipboard-check text-success me-2"></i> Keputusan Rekomendasi Penelaah</h5>
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Rekomendasi yang Dipilih oleh Penelaah:</label>
                <div class="p-3 bg-light rounded border fw-bold text-success fs-6">
                    {{ $tugas->rekomendasi ?? 'Belum ada rekomendasi' }}
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Catatan / Komentar Tambahan:</label>
                <div class="p-3 bg-light rounded border text-dark" style="min-height: 120px;">
                    {{ $tugas->catatan ?? 'Tidak ada catatan dari penelaah.' }}
                </div>
            </div>

            <div class="alert alert-info small mb-0">
                <i class="bi bi-info-circle-fill me-1"></i> Status Review: <strong class="text-uppercase">{{ $tugas->status_review }}</strong> pada {{ $tugas->tanggal_review ?? '-' }}
            </div>
        </div>
    </div>
</div>
@endsection