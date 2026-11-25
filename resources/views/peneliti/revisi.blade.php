@extends('layouts.app')

@section('title', 'Unggah Berkas Revisi')

@section('content')
<div class="mb-4">
    <a href="{{ route('peneliti.detail', $proposal->id) }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Usulan</a>
    <h3 class="fw-bold text-dark">Unggah Berkas Revisi Protokol</h3>
    <p class="text-muted mb-0">Kode Proposal: <strong class="text-primary">{{ $proposal->kode_proposal ?? '-' }}</strong></p>
</div>

@if(!empty($proposal->catatan_verifikasi))
    <div class="alert alert-warning shadow-sm border-0 rounded-3 mb-4 p-3 d-flex align-items-start gap-3">
        <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
        <div>
            <h6 class="fw-bold mb-1 text-dark">Catatan Rekomendasi Perbaikan dari Komite Etik:</h6>
            <p class="mb-0 small text-dark">{{ $proposal->catatan_verifikasi }}</p>
        </div>
    </div>
@endif

<div class="card card-modern p-4">
    <form action="{{ route('peneliti.revisi.update', $proposal->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4 p-3 rounded-3" style="background: #f1f5f9;">
            <div class="badge bg-light text-dark border mb-2">{{ $proposal->kode_proposal ?? '-' }}</div>
            <h5 class="fw-bold text-dark mb-1">{{ $proposal->judul_penelitian }}</h5>
            <small class="text-muted"><i class="bi bi-tag me-1"></i>{{ $proposal->jenisPenelitian?->nama_jenis ?? '-' }} &bull; <i class="bi bi-geo-alt me-1"></i>{{ $proposal->tempat_penelitian ?? '-' }}</small>
        </div>

        <div class="p-4 rounded-3 mb-4" style="background-color: #f8fafc; border: 1.5px dashed #cbd5e1;">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-cloud-arrow-up-fill text-teal me-2" style="color: #0d9488;"></i> Pilih Berkas yang Telah Diperbaiki</h6>
                <span class="badge bg-light text-muted border">Format PDF maks. 5 MB</span>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-medium text-dark small d-flex justify-content-between">
                    <span>1. Naskah Proposal Penelitian Revisi</span>
                    <span class="text-muted file-feedback"></span>
                </label>
                <input type="file" name="file_proposal" class="form-control form-control-modern bg-white file-pdf-check" accept=".pdf">
                <small class="text-muted">Biarkan kosong jika tidak ada perubahan pada berkas ini.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium text-dark small d-flex justify-content-between">
                    <span>2. Lembar Self-Assessment Revisi</span>
                    <span class="text-muted file-feedback"></span>
                </label>
                <input type="file" name="file_self_assessment" class="form-control form-control-modern bg-white file-pdf-check" accept=".pdf">
                <small class="text-muted">Biarkan kosong jika tidak ada perubahan.</small>
            </div>

            <div class="mb-0">
                <label class="form-label fw-medium text-dark small d-flex justify-content-between">
                    <span>3. Lampiran Pendukung / Informed Consent Revisi</span>
                    <span class="text-muted file-feedback"></span>
                </label>
                <input type="file" name="file_lampiran" class="form-control form-control-modern bg-white file-pdf-check" accept=".pdf">
                <small class="text-muted">Biarkan kosong jika tidak ada perubahan.</small>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('peneliti.detail', $proposal->id) }}" class="btn btn-light btn-modern border px-4">Batal</a>
            <button type="submit" class="btn btn-primary btn-modern px-4" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none;">
                <i class="bi bi-send-check me-1"></i> Simpan & Kirim Berkas Revisi
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInputs = document.querySelectorAll('.file-pdf-check');

        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                const feedbackSpan = this.parentElement.querySelector('.file-feedback');

                if (file) {
                    const fileSizeMb = (file.size / (1024 * 1024)).toFixed(2);
                    if (file.size > 5 * 1024 * 1024) {
                        alert(`Ukuran berkas "${file.name}" (${fileSizeMb} MB) melebihi batas 5 MB!`);
                        this.value = '';
                        if (feedbackSpan) {
                            feedbackSpan.innerHTML = '<span class="text-danger fw-semibold"><i class="bi bi-x-circle me-1"></i>Melebihi 5 MB!</span>';
                        }
                    } else {
                        if (feedbackSpan) {
                            feedbackSpan.innerHTML = `<span class="text-success fw-medium"><i class="bi bi-check-circle me-1"></i>${file.name} (${fileSizeMb} MB)</span>`;
                        }
                    }
                } else {
                    if (feedbackSpan) feedbackSpan.innerHTML = '';
                }
            });
        });
    });
</script>
@endpush
