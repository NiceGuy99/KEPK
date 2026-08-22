@extends('layouts.app')

@section('title', 'Pengajuan Usulan Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('peneliti.dashboard') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard</a>
    <h3 class="fw-bold" style="color: #1e293b;">Pengajuan Proposal Baru</h3>
    <p class="text-muted">Isi formulir di bawah ini dengan lengkap dan unggah seluruh berkas pendukung (Format PDF maks. 5 MB).</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-modern">
            <div class="card-header bg-white py-3" style="border-bottom: 1px solid #f1f5f9; border-radius: 16px 16px 0 0;">
                <h6 class="m-0 fw-bold" style="color: #0d9488;"><i class="bi bi-file-earmark-arrow-up me-2"></i> Formulir e-KEPK</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('peneliti.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">Judul Penelitian <span class="text-danger">*</span></label>
                        <textarea name="judul_penelitian" class="form-control form-control-modern" rows="3" required placeholder="Tuliskan judul lengkap penelitian Anda...">{{ old('judul_penelitian') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">Tempat Penelitian <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_penelitian" class="form-control form-control-modern" required placeholder="Contoh: RSUD Sidoarjo Barat / Rawat Inap Lt. 3..." value="{{ old('tempat_penelitian') }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark">Jenis Penelitian <span class="text-danger">*</span></label>
                        <select name="jenis_penelitian_id" class="form-select form-control-modern" required>
                            <option value="">-- Pilih Jenis Penelitian --</option>
                            @foreach($jenisPenelitian as $j)
                                <option value="{{ $j->id }}" {{ old('jenis_penelitian_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4 p-4 rounded-3" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-folder-plus text-primary me-2"></i> Unggah Dokumen Kelengkapan</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark small">1. Proposal Penelitian <span class="text-danger">* (Wajib)</span></label>
                            <input type="file" name="file_proposal" class="form-control form-control-modern bg-white" accept=".pdf" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark small">2. Self Assessment <span class="text-muted">(Opsional)</span></label>
                            <input type="file" name="file_self_assessment" class="form-control form-control-modern bg-white" accept=".pdf">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark small">3. Contoh Kuesioner / Pedoman Wawancara <span class="text-muted">(Opsional)</span></label>
                            <input type="file" name="file_kuesioner" class="form-control form-control-modern bg-white" accept=".pdf">
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-medium text-dark small">4. Lembar Informed Consent & Lampiran Lainnya <span class="text-muted">(Opsional)</span></label>
                            <input type="file" name="file_lampiran" class="form-control form-control-modern bg-white" accept=".pdf">
                        </div>

                        <div class="small text-muted mt-3"><i class="bi bi-info-circle me-1"></i> Format seluruh berkas wajib <b>.pdf</b> dengan ukuran maksimal <b>5 MB</b> per file.</div>
                    </div>

                    <hr class="my-4 text-muted">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('peneliti.dashboard') }}" class="btn btn-light btn-modern px-4 border">Batal</a>
                        <button type="submit" class="btn btn-primary btn-modern px-4 shadow-sm" style="background: #0d9488; border: none;">
                            Kirim Usulan <i class="bi bi-send ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card card-modern text-white h-100" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%);">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-lightbulb me-2"></i> Petunjuk Pengajuan</h5>
                <ul class="list-unstyled mb-0 small" style="opacity: 0.95; line-height: 1.8;">
                    <li class="mb-3 d-flex"><i class="bi bi-check-circle me-2 mt-1"></i> Pastikan naskah proposal penelitian lengkap (Bab 1 s/d Bab Metodologi Penelitian).</li>
                    <li class="mb-3 d-flex"><i class="bi bi-check-circle me-2 mt-1"></i> Seluruh berkas akan otomatis tersimpan dalam direktori terenkripsi kode usulan.</li>
                    <li class="mb-3 d-flex"><i class="bi bi-check-circle me-2 mt-1"></i> Sekretaris akan melakukan pengecekan administrasi awal sebelum penunjukan penelaah.</li>
                    <li class="d-flex"><i class="bi bi-check-circle me-2 mt-1"></i> Notifikasi perubahan status dapat dipantau langsung pada menu Status Proposal.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
