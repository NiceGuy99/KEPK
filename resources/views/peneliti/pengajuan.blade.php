@extends('layouts.app')

@section('title', 'Pengajuan Usulan Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('peneliti.dashboard') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard</a>
    <h3 class="fw-bold" style="color: #1e293b;">Pengajuan Protokol Penelitian Baru</h3>
    <p class="text-muted">Isi data protokol penelitian Anda dengan lengkap dan lampirkan berkas dokumen kelaikan etik (Format PDF maks. 5 MB).</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-modern shadow-sm border-0">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: rgba(13, 148, 136, 0.1); width: 36px; height: 36px; color: #0d9488;">
                        <i class="bi bi-file-earmark-medical-fill"></i>
                    </div>
                    <h6 class="m-0 fw-bold text-dark">Formulir Pendaftaran Protokol e-KEPK</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <form id="formPengajuan" action="{{ route('peneliti.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">
                            <i class="bi bi-card-heading text-teal me-1" style="color: #0d9488;"></i> Judul Penelitian <span class="text-danger">*</span>
                        </label>
                        <textarea name="judul_penelitian" class="form-control form-control-modern" rows="3" required placeholder="Tuliskan judul lengkap usulan penelitian Anda...">{{ old('judul_penelitian') }}</textarea>
                        <small class="text-muted">Gunakan huruf kapital sesuai kaidah penulisan judul.</small>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-geo-alt text-teal me-1" style="color: #0d9488;"></i> Tempat / Lokasi Penelitian <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="tempat_penelitian" class="form-control form-control-modern" required placeholder="Contoh: Instalasi Rawat Inap RSUD Sidoarjo Barat" value="{{ old('tempat_penelitian') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-tag text-teal me-1" style="color: #0d9488;"></i> Jenis Riset / Penelitian <span class="text-danger">*</span>
                            </label>
                            <select name="jenis_penelitian_id" class="form-select form-control-modern" required>
                                <option value="">-- Pilih Jenis Penelitian --</option>
                                @foreach($jenisPenelitian as $j)
                                    <option value="{{ $j->id }}" {{ old('jenis_penelitian_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- UPLOAD SECTION INTERAKTIF DENGAN DRAG & DROP --}}
                    <div class="mb-4 p-4 rounded-4" style="background-color: var(--surface-bg); border: 2px dashed var(--border-color);">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-cloud-arrow-up-fill text-teal me-2" style="color: #0d9488;"></i> Lampiran Berkas Protokol (Format PDF)
                            </h6>
                            <span class="badge bg-white text-muted border px-2.5 py-1.5 shadow-xs">Maks. 5 MB / Berkas</span>
                        </div>
                        
                        {{-- 1. Proposal Wajib --}}
                        <div class="mb-3 dropzone-wrapper" data-target="file_proposal">
                            <label class="form-label fw-bold text-dark small d-flex justify-content-between">
                                <span>1. Naskah Proposal Penelitian <span class="text-danger">* (Wajib)</span></span>
                                <span class="file-status-text text-muted" id="status-proposal">Belum ada file dipilih</span>
                            </label>
                            <div class="dropzone-box" onclick="document.getElementById('input_file_proposal').click()">
                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-2 d-block mb-1"></i>
                                <div class="fw-semibold text-dark small">Tarik & lepas file PDF di sini atau <span class="text-primary text-decoration-underline">Pilih dari Komputer</span></div>
                                <div class="text-muted" style="font-size: 0.72rem;">Hanya menerima file berekstensi .pdf (Maks. 5 MB)</div>
                            </div>
                            <input type="file" id="input_file_proposal" name="file_proposal" class="d-none file-interactive-input" accept=".pdf" required>
                            <div class="file-preview-card mt-2 d-none">
                                <div class="p-2.5 px-3 rounded-3 bg-white border d-flex align-items-center justify-content-between shadow-xs">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                        <div class="text-truncate" style="font-size: 0.82rem;">
                                            <strong class="file-name text-dark"></strong>
                                            <span class="file-size text-muted ms-1" style="font-size: 0.75rem;"></span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2 btn-remove-file" title="Hapus File"><i class="bi bi-trash3-fill"></i></button>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Lembar Self Assessment --}}
                        <div class="mb-3 dropzone-wrapper" data-target="file_self_assessment">
                            <label class="form-label fw-bold text-dark small d-flex justify-content-between">
                                <span>2. Lembar Self Assessment <span class="text-muted fw-normal">(Opsional)</span></span>
                                <span class="file-status-text text-muted" id="status-self">Belum ada file dipilih</span>
                            </label>
                            <div class="dropzone-box" onclick="document.getElementById('input_file_self').click()">
                                <i class="bi bi-file-earmark-check text-primary fs-2 d-block mb-1"></i>
                                <div class="fw-semibold text-dark small">Tarik & lepas file PDF di sini atau <span class="text-primary text-decoration-underline">Pilih dari Komputer</span></div>
                                <div class="text-muted" style="font-size: 0.72rem;">Format PDF (Maks. 5 MB)</div>
                            </div>
                            <input type="file" id="input_file_self" name="file_self_assessment" class="d-none file-interactive-input" accept=".pdf">
                            <div class="file-preview-card mt-2 d-none">
                                <div class="p-2.5 px-3 rounded-3 bg-white border d-flex align-items-center justify-content-between shadow-xs">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                        <div class="text-truncate" style="font-size: 0.82rem;">
                                            <strong class="file-name text-dark"></strong>
                                            <span class="file-size text-muted ms-1" style="font-size: 0.75rem;"></span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2 btn-remove-file" title="Hapus File"><i class="bi bi-trash3-fill"></i></button>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Kuesioner --}}
                        <div class="mb-3 dropzone-wrapper" data-target="file_kuesioner">
                            <label class="form-label fw-bold text-dark small d-flex justify-content-between">
                                <span>3. Contoh Kuesioner / Pedoman Wawancara <span class="text-muted fw-normal">(Opsional)</span></span>
                                <span class="file-status-text text-muted" id="status-kues">Belum ada file dipilih</span>
                            </label>
                            <div class="dropzone-box" onclick="document.getElementById('input_file_kues').click()">
                                <i class="bi bi-file-earmark-ruled text-info fs-2 d-block mb-1"></i>
                                <div class="fw-semibold text-dark small">Tarik & lepas file PDF di sini atau <span class="text-primary text-decoration-underline">Pilih dari Komputer</span></div>
                                <div class="text-muted" style="font-size: 0.72rem;">Format PDF (Maks. 5 MB)</div>
                            </div>
                            <input type="file" id="input_file_kues" name="file_kuesioner" class="d-none file-interactive-input" accept=".pdf">
                            <div class="file-preview-card mt-2 d-none">
                                <div class="p-2.5 px-3 rounded-3 bg-white border d-flex align-items-center justify-content-between shadow-xs">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                        <div class="text-truncate" style="font-size: 0.82rem;">
                                            <strong class="file-name text-dark"></strong>
                                            <span class="file-size text-muted ms-1" style="font-size: 0.75rem;"></span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2 btn-remove-file" title="Hapus File"><i class="bi bi-trash3-fill"></i></button>
                                </div>
                            </div>
                        </div>

                        {{-- 4. Informed Consent & Lampiran Lainnya --}}
                        <div class="mb-0 dropzone-wrapper" data-target="file_lampiran">
                            <label class="form-label fw-bold text-dark small d-flex justify-content-between">
                                <span>4. Lembar Informed Consent & Lampiran Lainnya <span class="text-muted fw-normal">(Opsional)</span></span>
                                <span class="file-status-text text-muted" id="status-lamp">Belum ada file dipilih</span>
                            </label>
                            <div class="dropzone-box" onclick="document.getElementById('input_file_lamp').click()">
                                <i class="bi bi-file-earmark-plus text-warning fs-2 d-block mb-1"></i>
                                <div class="fw-semibold text-dark small">Tarik & lepas file PDF di sini atau <span class="text-primary text-decoration-underline">Pilih dari Komputer</span></div>
                                <div class="text-muted" style="font-size: 0.72rem;">Format PDF (Maks. 5 MB)</div>
                            </div>
                            <input type="file" id="input_file_lamp" name="file_lampiran" class="d-none file-interactive-input" accept=".pdf">
                            <div class="file-preview-card mt-2 d-none">
                                <div class="p-2.5 px-3 rounded-3 bg-white border d-flex align-items-center justify-content-between shadow-xs">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                        <div class="text-truncate" style="font-size: 0.82rem;">
                                            <strong class="file-name text-dark"></strong>
                                            <span class="file-size text-muted ms-1" style="font-size: 0.75rem;"></span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2 btn-remove-file" title="Hapus File"><i class="bi bi-trash3-fill"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 shadow-xs rounded-3 d-flex align-items-center gap-2 p-3 mb-4">
                        <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
                        <span class="small">
                            Pastikan berkas telah sesuai pedoman etika penelitian. Setelah dikirim, sekretariat KEPK akan memeriksa kelengkapan administrasi sebelum menugaskan penelaah.
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('peneliti.dashboard') }}" class="btn btn-light btn-modern px-4 border">Batal</a>
                        <button type="submit" id="btnSubmitProposal" class="btn btn-primary btn-modern px-4 shadow-sm" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none;">
                            <i class="bi bi-send-check-fill me-1"></i> Kirim Usulan Protokol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card card-modern text-white mb-4" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none; border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle p-2 bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-lightbulb-fill fs-5 text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Petunjuk Pengajuan</h5>
                </div>
                <ul class="list-unstyled mb-0 small" style="opacity: 0.95; line-height: 1.8;">
                    <li class="mb-3 d-flex"><i class="bi bi-check2-circle me-2 mt-1 fs-6"></i> Naskah proposal harus memuat Latar Belakang, Tujuan, Metode, serta Aspek Etik Penelitian.</li>
                    <li class="mb-3 d-flex"><i class="bi bi-check2-circle me-2 mt-1 fs-6"></i> Seluruh file wajib berformat PDF agar terbaca sempurna saat telaah online.</li>
                    <li class="mb-3 d-flex"><i class="bi bi-check2-circle me-2 mt-1 fs-6"></i> Batas ukuran berkas maksimum 5 Megabytes (MB) per file.</li>
                    <li class="d-flex"><i class="bi bi-check2-circle me-2 mt-1 fs-6"></i> Pantau proses telaah kapan saja melalui menu <b>Status Proposal</b>.</li>
                </ul>
            </div>
        </div>

        <div class="card card-modern p-4 border-0">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-question-circle text-primary me-2"></i> Butuh Bantuan?</h6>
            <p class="text-muted small mb-3">Jika mengalami kendala pengunggahan berkas atau ingin berkonsultasi, hubungi Sekretariat Komite Etik Penelitian Kesehatan RSUD Sidoarjo Barat.</p>
            <div class="d-flex align-items-center gap-2 text-dark small fw-medium">
                <i class="bi bi-envelope text-teal" style="color: #0d9488;"></i> sekretariat.kepk@rsudsidoarjo.id
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const wrappers = document.querySelectorAll('.dropzone-wrapper');

        wrappers.forEach(wrapper => {
            const dropzone = wrapper.querySelector('.dropzone-box');
            const input = wrapper.querySelector('.file-interactive-input');
            const previewCard = wrapper.querySelector('.file-preview-card');
            const fileNameEl = wrapper.querySelector('.file-name');
            const fileSizeEl = wrapper.querySelector('.file-size');
            const statusText = wrapper.querySelector('.file-status-text');
            const btnRemove = wrapper.querySelector('.btn-remove-file');

            function handleFile(file) {
                if (!file) return;

                if (!file.name.toLowerCase().endsWith('.pdf')) {
                    showToast('error', 'Format file tidak didukung! Harap unggah dokumen berekstensi .pdf.');
                    input.value = '';
                    return;
                }

                const maxBytes = 5 * 1024 * 1024;
                const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                if (file.size > maxBytes) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar',
                        text: `Berkas "${file.name}" berukuran ${sizeMb} MB. Batas maksimal yang diperbolehkan adalah 5 MB.`,
                        confirmButtonColor: '#0d9488'
                    });
                    input.value = '';
                    return;
                }

                // Valid file selected
                if (fileNameEl) fileNameEl.textContent = file.name;
                if (fileSizeEl) fileSizeEl.textContent = `(${sizeMb} MB)`;
                if (statusText) statusText.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle me-1"></i>Siap diunggah</span>';

                dropzone.classList.add('d-none');
                previewCard.classList.remove('d-none');
                showToast('success', `Berkas ${file.name} berhasil dipilih.`);
            }

            // Input change
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    handleFile(this.files[0]);
                }
            });

            // Drag and drop events
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('is-dragover');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('is-dragover');
                }, false);
            });

            dropzone.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files && files.length > 0) {
                    input.files = files;
                    handleFile(files[0]);
                }
            });

            // Remove file button
            if (btnRemove) {
                btnRemove.addEventListener('click', function(e) {
                    e.preventDefault();
                    input.value = '';
                    previewCard.classList.add('d-none');
                    dropzone.classList.remove('d-none');
                    if (statusText) statusText.innerHTML = 'Belum ada file dipilih';
                });
            }
        });

        // Form Submit Confirmation via SweetAlert2
        const formPengajuan = document.getElementById('formPengajuan');
        const btnSubmit = document.getElementById('btnSubmitProposal');

        if (formPengajuan) {
            formPengajuan.addEventListener('submit', function(e) {
                e.preventDefault();

                const fileProposalInput = document.getElementById('input_file_proposal');
                if (!fileProposalInput || !fileProposalInput.files || fileProposalInput.files.length === 0) {
                    showToast('error', 'Naskah Proposal Penelitian wajib diunggah!');
                    return;
                }

                confirmAction({
                    title: 'Kirim Usulan Protokol?',
                    text: 'Pastikan data dan dokumen kelaikan etik sudah lengkap dan sesuai panduan.',
                    icon: 'question',
                    confirmBtnText: 'Ya, Kirim Sekarang',
                    confirmBtnColor: '#0d9488'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (btnSubmit) {
                            btnSubmit.disabled = true;
                            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengunggah & Memproses...';
                        }
                        formPengajuan.submit();
                    }
                });
            });
        }
    });
</script>
@endpush
