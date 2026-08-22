@extends('layouts.app')

@section('title', 'Unggah Berkas Revisi')

@section('content')
<div class="mb-4">
    <a href="{{ route('peneliti.detail', $proposal->id) }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Usulan</a>
    <h3 class="fw-bold text-dark">Unggah Berkas Revisi Usulan</h3>
    <p class="text-muted mb-0">Kode Proposal: <strong>{{ $proposal->kode_proposal ?? '-' }}</strong></p>
</div>

@if(!empty($proposal->catatan_verifikasi))
    <div class="alert alert-warning shadow-sm border-0 rounded-3 mb-4">
        <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Catatan / Rekomendasi Perbaikan:</h6>
        <p class="mb-0 small">{{ $proposal->catatan_verifikasi }}</p>
    </div>
@endif

<div class="card card-modern p-4">
    <form action="{{ route('peneliti.revisi.update', $proposal->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            <h5 class="fw-bold text-dark mb-1">{{ $proposal->judul_penelitian }}</h5>
            <small class="text-muted">{{ $proposal->jenisPenelitian?->nama_jenis ?? '-' }} | {{ $proposal->tempat_penelitian ?? '-' }}</small>
        </div>

        <div class="p-4 rounded-3 mb-4" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i> Pilih Berkas yang Direvisi (Format PDF maks. 5 MB)</h6>
            
            <div class="mb-3">
                <label class="form-label fw-medium text-dark small">1. Naskah Proposal Penelitian Revisi</label>
                <input type="file" name="file_proposal" class="form-control form-control-modern bg-white" accept=".pdf">
                <small class="text-muted">Biarkan kosong jika tidak ada perubahan pada berkas ini.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium text-dark small">2. Lembar Self-Assessment Revisi</label>
                <input type="file" name="file_self_assessment" class="form-control form-control-modern bg-white" accept=".pdf">
                <small class="text-muted">Biarkan kosong jika tidak ada perubahan.</small>
            </div>

            <div class="mb-0">
                <label class="form-label fw-medium text-dark small">3. Lampiran Pendukung / Informed Consent Revisi</label>
                <input type="file" name="file_lampiran" class="form-control form-control-modern bg-white" accept=".pdf">
                <small class="text-muted">Biarkan kosong jika tidak ada perubahan.</small>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('peneliti.detail', $proposal->id) }}" class="btn btn-light btn-modern border px-4">Batal</a>
            <button type="submit" class="btn btn-primary btn-modern px-4" style="background: #0d9488; border: none;">
                <i class="bi bi-send-check me-1"></i> Simpan & Kirim Berkas Revisi
            </button>
        </div>
    </form>
</div>
@endsection
