@extends('layouts.app')

@section('title', 'Lembar Telaah Etik')

@section('content')
<div class="mb-4">
    <a href="{{ route('penelaah.tugas') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Tugas</a>
    <h3 class="fw-bold text-dark mb-1">Lembar Kerja Telaah Kelaikan Etik</h3>
    <p class="text-muted mb-0">Kode Proposal: <strong>{{ $penugasan->proposal?->kode_proposal ?? '-' }}</strong></p>
</div>

@php
    $isSelesai = ($penugasan->status_review === 'selesai');
@endphp

@if($isSelesai)
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        <i class="bi bi-lock-fill me-2"></i> Lembar telaah ini sudah dikunci karena Anda telah menyelesaikan proses penilaian.
    </div>
@endif

<div class="row">
    <div class="col-lg-6">
        <div class="card card-modern p-4 mb-4">
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i> Informasi Penelitian</h6>
            <table class="table table-borderless small mb-0">
                <tr><th width="150" class="text-muted">Judul Penelitian:</th><td class="fw-bold">{{ $penugasan->proposal?->judul_penelitian }}</td></tr>
                <tr><th class="text-muted">Peneliti Utama:</th><td>{{ $penugasan->proposal?->peneliti?->nama_lengkap }}</td></tr>
                <tr><th class="text-muted">Instansi / Kampus:</th><td>{{ $penugasan->proposal?->peneliti?->profil?->asal_kampus ?? '-' }}</td></tr>
                <tr><th class="text-muted">Tempat Riset:</th><td>{{ $penugasan->proposal?->tempat_penelitian ?? '-' }}</td></tr>
                <tr><th class="text-muted">Jenis Riset:</th><td>{{ $penugasan->proposal?->jenisPenelitian?->nama_jenis ?? '-' }}</td></tr>
            </table>

            <hr class="my-3">

            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-folder2-open me-2"></i> Berkas Protokol Peneliti</h6>
            <div class="list-group small mb-3">
                @if(!empty($penugasan->proposal?->file_proposal))
                    <a href="{{ asset('uploads/proposal/' . $penugasan->proposal->file_proposal) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-file-pdf text-danger me-2"></i> 1. Proposal Penelitian</span>
                        <span class="badge bg-primary rounded-pill">Buka PDF</span>
                    </a>
                @endif
                @if(!empty($penugasan->proposal?->file_self_assessment))
                    <a href="{{ asset('uploads/proposal/' . $penugasan->proposal->file_self_assessment) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-file-pdf text-danger me-2"></i> 2. Self Assessment Peneliti</span>
                        <span class="badge bg-primary rounded-pill">Buka PDF</span>
                    </a>
                @endif
                @if(!empty($penugasan->proposal?->file_kuesioner))
                    <a href="{{ asset('uploads/proposal/' . $penugasan->proposal->file_kuesioner) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-file-pdf text-danger me-2"></i> 3. Instrumen / Kuesioner</span>
                        <span class="badge bg-primary rounded-pill">Buka PDF</span>
                    </a>
                @endif
                @if(!empty($penugasan->proposal?->file_lampiran))
                    <a href="{{ asset('uploads/proposal/' . $penugasan->proposal->file_lampiran) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-file-pdf text-danger me-2"></i> 4. Informed Consent & Lampiran</span>
                        <span class="badge bg-primary rounded-pill">Buka PDF</span>
                    </a>
                @endif
            </div>

            <a href="{{ route('penelaah.form7standar', $penugasan->id) }}" class="btn btn-outline-info w-100 py-2 btn-modern">
                <i class="bi bi-ui-checks-grid me-2"></i> Buka Formulir Daftar Tilik 7 Standar Etik ({{ count($savedAnswers) }} Butir Terisi)
            </a>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-modern p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-send-check text-primary me-2"></i> Keputusan Rekomendasi Penelaah</h6>
            
            <form action="{{ route('penelaah.telaah.simpan', $penugasan->id) }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label small fw-medium">Rekomendasi Penelaah *</label>
                    <select name="rekomendasi" class="form-select form-control-modern" required {{ $isSelesai ? 'disabled' : '' }}>
                        <option value="">-- Pilih Rekomendasi Telaah --</option>
                        <option value="Exempted" {{ $penugasan->rekomendasi == 'Exempted' ? 'selected' : '' }}>1. Exempted (Bebas Kaji Etik)</option>
                        <option value="Expedited" {{ $penugasan->rekomendasi == 'Expedited' ? 'selected' : '' }}>2. Expedited (Telaah Cepat / Dipercepat)</option>
                        <option value="Full Board" {{ $penugasan->rekomendasi == 'Full Board' ? 'selected' : '' }}>3. Full Board (Perlu Sidang Pleno)</option>
                        <option value="Revisi Protokol" {{ $penugasan->rekomendasi == 'Revisi Protokol' ? 'selected' : '' }}>4. Perlu Perbaikan / Revisi Protokol</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-medium">Catatan / Telaahan Khusus Penelaah</label>
                    <textarea name="catatan" class="form-control form-control-modern" rows="5" placeholder="Tuliskan alasan pertimbangan, poin-poin saran perbaikan etika riset..." {{ $isSelesai ? 'disabled' : '' }}>{{ old('catatan', $penugasan->catatan) }}</textarea>
                </div>

                @if(!$isSelesai)
                    <button type="submit" class="btn btn-primary btn-modern w-100 py-2" style="background: #0d9488; border: none;">
                        <i class="bi bi-check2-circle me-1"></i> Simpan & Serahkan Hasil Telaah
                    </button>
                @else
                    <div class="alert alert-success border-0 small text-center mb-0">
                        <i class="bi bi-check-all me-1"></i> Hasil telaah ini telah diserahkan.
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection