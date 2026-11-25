@extends('layouts.app')

@section('title', 'WhatsApp Gateway & Template Management')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-whatsapp text-success me-2"></i> WhatsApp Gateway & Template Notifikasi
        </h3>
        <p class="text-muted mb-0">Kelola server pengiriman WhatsApp self-hosted gratis dan atur kata-kata pesan otomatis untuk setiap skenario.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-teal text-white btn-modern px-3.5 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPemicu" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none;">
            <i class="bi bi-plus-circle me-1.5"></i> Tambah Pemicu Baru
        </button>
        <a href="{{ $gatewayUrl }}/qr" target="_blank" class="btn btn-outline-secondary btn-modern px-3 py-2">
            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Layar QR Mandiri
        </a>
    </div>
</div>

@if(session('pesan_sukses'))
    <div class="alert alert-success border-0 rounded-4 p-3 mb-4 d-flex align-items-center gap-3 shadow-sm" style="background: rgba(16, 185, 129, 0.1);">
        <i class="bi bi-check-circle-fill fs-4 text-success"></i>
        <div class="text-dark fw-medium">{{ session('pesan_sukses') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('pesan_error'))
    <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 d-flex align-items-center gap-3 shadow-sm" style="background: rgba(239, 68, 68, 0.1);">
        <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
        <div class="text-dark fw-medium">{{ session('pesan_error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4 mb-4">
    <!-- Kartu Status Server Gateway & Scan QR -->
    <div class="col-lg-7">
        <div class="card card-modern p-4 border-0 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-router text-primary me-2"></i> Koneksi Gateway WhatsApp
                </h5>
                <div id="liveBadgeStatus">
                    @if(isset($status['connected']) && $status['connected'])
                        <span class="badge bg-success-subtle text-success border border-success px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                            <span class="pulse-indicator bg-success"></span>
                            <span class="fw-bold">ONLINE & TERHUBUNG</span>
                        </span>
                    @else
                        <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                            <span class="pulse-indicator bg-warning"></span>
                            <span class="fw-bold">MENUNGGU KONEKSI / OFFLINE</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Area Live Widget Koneksi (Otomatis Polling dari Gateway) -->
            <div id="gatewayWidgetContainer">
                @if(isset($status['connected']) && $status['connected'])
                    <div class="alert alert-success border-0 rounded-4 p-4 mb-3" style="background: rgba(16, 185, 129, 0.08);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 52px; height: 52px; background: #10b981;">
                                <i class="bi bi-whatsapp fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-success mb-1">WhatsApp Siap Mengirim Notifikasi</h6>
                                <div class="text-dark small">Nomor Aktif: <strong>+{{ $status['user']['phone'] ?? '-' }}</strong></div>
                                <small class="text-muted">Pesan otomatis akan dikirimkan langsung dari nomor resmi ini ke Peneliti & Penelaah.</small>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card border border-dashed rounded-4 p-4 text-center bg-light mb-3" id="qrCardBox">
                        <div id="qrContentArea">
                            <div class="spinner-border text-teal mb-3" style="color: #0d9488;" role="status"></div>
                            <h6 class="fw-bold text-dark mb-1">Mengecek Sambungan ke Gateway...</h6>
                            <p class="text-muted small mb-0">Menghubungkan ke <code>{{ $gatewayUrl }}</code></p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top">
                <small class="text-muted" style="font-size: 0.78rem;">
                    <i class="bi bi-shield-check text-success me-1"></i> Self-hosted Baileys Gateway (Port: <strong>8088</strong> &bull; Biaya Rp 0,-)
                </small>
                <div class="d-flex gap-2">
                    <button type="button" onclick="triggerResetQr()" class="btn btn-sm btn-outline-warning rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                        <i class="bi bi-arrow-repeat me-1"></i> Reset Sesi & Buat QR Baru
                    </button>
                    <button type="button" onclick="checkGatewayStatusLive(true)" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                        <i class="bi bi-arrow-clockwise me-1"></i> Cek Ulang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Uji Coba Pengiriman Pesan -->
    <div class="col-lg-5">
        <div class="card card-modern p-4 border-0 h-100">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-teal" style="width: 40px; height: 40px; background: rgba(13, 148, 136, 0.1); color: #0d9488;">
                    <i class="bi bi-send-fill fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Uji Coba Pengiriman</h5>
                    <small class="text-muted">Kirim pesan pengujian langsung ke WhatsApp Anda</small>
                </div>
            </div>

            <form action="{{ route('sekretaris.wa-gateway.test') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">Nomor WhatsApp Tujuan <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control form-control-modern" placeholder="Contoh: 081234567890" required value="{{ old('phone') }}">
                    <small class="text-muted" style="font-size: 0.72rem;">Format: 08xx atau 628xx</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark small">Isi Pesan Uji Coba <span class="text-danger">*</span></label>
                    <textarea name="message" class="form-control form-control-modern" rows="3" required placeholder="Tulis pesan pengujian di sini...">Halo! Ini adalah pesan uji coba dari Sistem e-KEPK ETHOS RSUD Sidoarjo Barat.</textarea>
                </div>

                <button type="submit" class="btn btn-modern w-100 py-2 text-white shadow-sm" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none;">
                    <i class="bi bi-send-check me-1"></i> Kirim Pesan Uji Coba Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- TABEL MANAJEMEN TEMPLATE & SKENARIO NOTIFIKASI OTOMATIS (OPSI 2) -->
<!-- ========================================================================= -->
<div class="card card-modern p-4 border-0 shadow-sm mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">
                <i class="bi bi-chat-left-dots-fill text-warning me-2"></i> Pengaturan Template & Pemicu Pesan Otomatis
            </h5>
            <p class="text-muted small mb-0">Sesuaikan kalimat pesan, aktifkan/nonaktifkan skenario tertentu, dan gunakan variabel dinamis seperti <code>{nama_lengkap}</code>, <code>{judul_penelitian}</code>, <code>{kode_proposal}</code>.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted small text-uppercase fw-bold">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Pemicu / Aktivitas</th>
                    <th style="width: 140px;">Penerima</th>
                    <th>Ringkasan Pesan Notifikasi</th>
                    <th style="width: 110px;" class="text-center">Status</th>
                    <th style="width: 140px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $index => $tpl)
                    <tr>
                        <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark mb-0.5">{{ $tpl->nama_pemicu }}</div>
                            <small class="text-muted d-block text-truncate" style="max-width: 280px;" title="{{ $tpl->deskripsi }}">
                                {{ $tpl->deskripsi ?? 'Tidak ada deskripsi' }}
                            </small>
                            <span class="badge bg-light text-secondary border font-monospace mt-1" style="font-size: 0.7rem;">
                                slug: {{ $tpl->slug }}
                            </span>
                        </td>
                        <td>
                            @if(strcasecmp($tpl->target_role, 'Peneliti') === 0)
                                <span class="badge bg-primary-subtle text-primary border border-primary px-2.5 py-1 rounded-pill">
                                    <i class="bi bi-person me-1"></i> Peneliti
                                </span>
                            @elseif(strcasecmp($tpl->target_role, 'Penelaah') === 0)
                                <span class="badge bg-info-subtle text-info border border-info px-2.5 py-1 rounded-pill">
                                    <i class="bi bi-journal-check me-1"></i> Penelaah
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning px-2.5 py-1 rounded-pill">
                                    <i class="bi bi-building me-1"></i> Sekretariat
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="small text-dark p-2.5 rounded-3 bg-light border font-monospace" style="white-space: pre-line; max-height: 85px; overflow-y: auto; font-size: 0.78rem; line-height: 1.45;">
                                {{ Str::limit($tpl->template_pesan, 180) }}
                            </div>
                        </td>
                        <td class="text-center">
                            <!-- Switch ON/OFF -->
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input switch-toggle-template" type="checkbox" role="switch" 
                                       data-id="{{ $tpl->id }}" 
                                       id="switchTpl{{ $tpl->id }}" 
                                       {{ $tpl->is_active ? 'checked' : '' }} 
                                       style="width: 2.8rem; height: 1.4rem; cursor: pointer;">
                            </div>
                            <small class="d-block text-muted mt-1 fw-semibold status-label-{{ $tpl->id }}" style="font-size: 0.72rem;">
                                {{ $tpl->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </small>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-modern px-3 py-1.5 rounded-pill" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditTemplate{{ $tpl->id }}">
                                <i class="bi bi-pencil-square me-1"></i> Edit Pesan
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada template notifikasi yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL EDIT TEMPLATES (Placed outside table container) -->
<!-- ========================================================================= -->
@foreach($templates as $tpl)
    <div class="modal fade" id="modalEditTemplate{{ $tpl->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $tpl->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <form action="{{ route('sekretaris.wa-gateway.template.update', $tpl->id) }}" method="POST">
                    @csrf
                    <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%);">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-chat-square-text-fill fs-5"></i>
                            <h5 class="modal-title fw-bold" id="modalLabel{{ $tpl->id }}">Edit Template: {{ $tpl->nama_pemicu }}</h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label class="form-label fw-semibold text-dark small">Nama Pemicu / Aktivitas <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pemicu" class="form-control form-control-modern" value="{{ $tpl->nama_pemicu }}" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold text-dark small">Target Penerima <span class="text-danger">*</span></label>
                                <select name="target_role" class="form-select form-control-modern" required>
                                    <option value="Peneliti" {{ strcasecmp($tpl->target_role, 'Peneliti') === 0 ? 'selected' : '' }}>Peneliti</option>
                                    <option value="Penelaah" {{ strcasecmp($tpl->target_role, 'Penelaah') === 0 ? 'selected' : '' }}>Penelaah / Reviewer</option>
                                    <option value="Sekretariat" {{ strcasecmp($tpl->target_role, 'Sekretariat') === 0 ? 'selected' : '' }}>Sekretariat</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Deskripsi Singkat</label>
                            <input type="text" name="deskripsi" class="form-control form-control-modern" value="{{ $tpl->deskripsi }}" placeholder="Tujuan pengiriman notifikasi ini...">
                        </div>

                        <!-- Variabel Dinamis Helper Bar -->
                        <div class="p-3 rounded-3 mb-3 border" style="background: rgba(13, 148, 136, 0.05); border-color: rgba(13, 148, 136, 0.2) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <small class="fw-bold text-teal" style="color: #0d9488;">
                                    <i class="bi bi-braces-asterisk me-1"></i> Klik variabel di bawah untuk menyisipkan otomatis ke teks:
                                </small>
                            </div>
                            <div class="d-flex flex-wrap gap-1.5">
                                @php
                                    $vars = array_map('trim', explode(',', $tpl->variabel_tersedia ?: '{nama_lengkap},{judul_penelitian},{kode_proposal},{tgl}'));
                                @endphp
                                @foreach($vars as $v)
                                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-2.5 py-1 text-dark fw-medium" 
                                            style="font-size: 0.76rem;" 
                                            onclick="insertVariable('textareaTpl{{ $tpl->id }}', '{{ $v }}')">
                                        <i class="bi bi-plus text-primary me-0.5"></i><code>{{ $v }}</code>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Template Isi Pesan WhatsApp <span class="text-danger">*</span></label>
                            <textarea name="template_pesan" id="textareaTpl{{ $tpl->id }}" class="form-control font-monospace p-3" rows="10" required style="font-size: 0.85rem; line-height: 1.6; border-radius: 12px;">{{ $tpl->template_pesan }}</textarea>
                            <div class="d-flex justify-content-between text-muted small mt-1.5" style="font-size: 0.72rem;">
                                <span>Tips: Gunakan format WhatsApp: <code>*tebal*</code>, <code>_miring_</code>, <code>~coret~</code></span>
                                <span id="charCount{{ $tpl->id }}">{{ strlen($tpl->template_pesan) }} karakter</span>
                            </div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeCheck{{ $tpl->id }}" {{ $tpl->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark small" for="activeCheck{{ $tpl->id }}">
                                Aktifkan pemicu notifikasi otomatis ini
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 py-3 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-modern px-3.5" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-teal text-white btn-modern px-4" style="background: #0d9488; border: none;">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- MODAL TAMBAH PEMICU BARU -->
<div class="modal fade" id="modalTambahPemicu" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <form action="{{ route('sekretaris.wa-gateway.template.store') }}" method="POST">
                @csrf
                <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle-fill fs-5"></i>
                        <h5 class="modal-title fw-bold" id="modalTambahLabel">Tambah Pemicu Notifikasi Baru</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-semibold text-dark small">Nama Pemicu / Aktivitas <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pemicu" class="form-control form-control-modern" placeholder="Contoh: Pengingat Batas Telaah Penelaah" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold text-dark small">Slug Unik Sistem <span class="text-danger">*</span></label>
                            <input type="text" name="slug" class="form-control form-control-modern" placeholder="Contoh: reminder_penelaah" required>
                            <small class="text-muted" style="font-size: 0.7rem;">Hanya huruf kecil dan garis bawah (_)</small>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Target Penerima <span class="text-danger">*</span></label>
                            <select name="target_role" class="form-select form-control-modern" required>
                                <option value="Peneliti">Peneliti</option>
                                <option value="Penelaah">Penelaah / Reviewer</option>
                                <option value="Sekretariat">Sekretariat</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Daftar Variabel Tersedia</label>
                            <input type="text" name="variabel_tersedia" class="form-control form-control-modern" value="{nama_lengkap}, {judul_penelitian}, {kode_proposal}, {tgl}">
                            <small class="text-muted" style="font-size: 0.7rem;">Pisahkan dengan koma</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Deskripsi Singkat</label>
                        <input type="text" name="deskripsi" class="form-control form-control-modern" placeholder="Kapan pesan ini dipicu dan dikirimkan...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Template Isi Pesan WhatsApp <span class="text-danger">*</span></label>
                        <textarea name="template_pesan" class="form-control font-monospace p-3" rows="8" required style="font-size: 0.85rem; line-height: 1.6; border-radius: 12px;" placeholder="Tuliskan format pesan WhatsApp di sini... Gunakan variabel seperti {nama_lengkap}, {kode_proposal}, {judul_penelitian}"></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-modern px-3.5" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-teal text-white btn-modern px-4" style="background: #0d9488; border: none;">
                        <i class="bi bi-save me-1"></i> Simpan Pemicu Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. Helper fungsi untuk menyisipkan variabel di kursor textarea
    function insertVariable(textareaId, variableText) {
        const textarea = document.getElementById(textareaId);
        if (!textarea) return;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;

        textarea.value = text.substring(0, start) + variableText + text.substring(end);
        textarea.selectionStart = textarea.selectionEnd = start + variableText.length;
        textarea.focus();
    }

    // 2. AJAX Toggle Switch Status Template
    document.querySelectorAll('.switch-toggle-template').forEach(function(switchElem) {
        switchElem.addEventListener('change', async function() {
            const templateId = this.dataset.id;
            const isChecked = this.checked;
            const statusLabel = document.querySelector('.status-label-' + templateId);

            try {
                const response = await fetch(`/sekretaris/whatsapp-gateway/template/${templateId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();

                if (data.success) {
                    if (statusLabel) {
                        statusLabel.textContent = data.is_active ? 'AKTIF' : 'NONAKTIF';
                        statusLabel.className = `d-block mt-1 fw-semibold status-label-${templateId} ${data.is_active ? 'text-success' : 'text-muted'}`;
                    }
                    if (window.Swal) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });
                    }
                } else {
                    throw new Error(data.message || 'Gagal mengubah status');
                }
            } catch (err) {
                this.checked = !isChecked; // rollback switch
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal memperbarui status template notifikasi: ' + err.message
                    });
                }
            }
        });
    });

    // 3. Live Polling WhatsApp Gateway Status & Direct Embedded QR Scan
    const GATEWAY_URL = "{{ $gatewayUrl }}";
    let isConnectedGlobal = {{ (isset($status['connected']) && $status['connected']) ? 'true' : 'false' }};
    let currentRawQr = null;

    async function checkGatewayStatusLive(isManual = false) {
        const badgeElem = document.getElementById('liveBadgeStatus');
        const container = document.getElementById('gatewayWidgetContainer');

        try {
            const res = await fetch(`${GATEWAY_URL}/status`, { cache: 'no-store' });
            if (!res.ok) throw new Error('Status code ' + res.status);
            const data = await res.json();

            if (data.connected) {
                isConnectedGlobal = true;
                badgeElem.innerHTML = `
                    <span class="badge bg-success-subtle text-success border border-success px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                        <span class="pulse-indicator bg-success"></span>
                        <span class="fw-bold">ONLINE & TERHUBUNG</span>
                    </span>
                `;
                container.innerHTML = `
                    <div class="alert alert-success border-0 rounded-4 p-4 mb-3" style="background: rgba(16, 185, 129, 0.08);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 52px; height: 52px; background: #10b981;">
                                <i class="bi bi-whatsapp fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-success mb-1">WhatsApp Siap Mengirim Notifikasi</h6>
                                <div class="text-dark small">Nomor Aktif: <strong>+${data.user?.phone || '-'}</strong></div>
                                <small class="text-muted">Pesan otomatis akan dikirimkan langsung dari nomor resmi ini ke Peneliti & Penelaah.</small>
                            </div>
                        </div>
                    </div>
                `;
            } else if (data.has_qr && data.qr_image) {
                isConnectedGlobal = false;
                badgeElem.innerHTML = `
                    <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                        <span class="pulse-indicator bg-warning"></span>
                        <span class="fw-bold">SIAP SCAN QR CODE</span>
                    </span>
                `;

                if (currentRawQr !== data.qr_raw) {
                    currentRawQr = data.qr_raw;
                    container.innerHTML = `
                        <div class="card border border-primary border-opacity-25 rounded-4 p-4 text-center bg-white mb-3 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-5 text-center mb-3 mb-md-0">
                                    <div class="p-2 border rounded-3 d-inline-block shadow-sm bg-white">
                                        <img src="${data.qr_image}" alt="Scan QR Code WA" class="img-fluid" style="width: 200px; height: 200px; border-radius: 8px;">
                                    </div>
                                    <div class="text-muted mt-2" style="font-size: 0.72rem;">
                                        <i class="bi bi-arrow-repeat me-1 text-primary"></i> Kode QR akan diperbarui otomatis
                                    </div>
                                </div>
                                <div class="col-md-7 text-start ps-md-3">
                                    <h6 class="fw-bold text-dark mb-2">
                                        <i class="bi bi-qr-code-scan text-teal me-1.5"></i> Tautkan Nomor WhatsApp Resmi:
                                    </h6>
                                    <ol class="small text-muted ps-3 mb-3" style="line-height: 1.7; font-size: 0.82rem;">
                                        <li>Buka WhatsApp di HP KEPK/Rumah Sakit</li>
                                        <li>Buka <b>Titik Tiga</b> atau <b>Pengaturan</b> &rarr; <b>Perangkat Tertaut</b></li>
                                        <li>Ketuk <b>Tautkan Perangkat</b> lalu arahkan kamera HP ke QR Code ini</li>
                                    </ol>
                                    <div class="d-flex gap-2">
                                        <a href="${GATEWAY_URL}/qr" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5" style="font-size: 0.78rem;">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Layar Penuh
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            } else {
                isConnectedGlobal = false;
                badgeElem.innerHTML = `
                    <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                        <span class="pulse-indicator bg-warning"></span>
                        <span class="fw-bold">MENGHUBUNGKAN SERVER...</span>
                    </span>
                `;
                container.innerHTML = `
                    <div class="card border border-dashed rounded-4 p-4 text-center bg-light mb-3">
                        <div class="spinner-border text-teal mb-3" style="color: #0d9488;" role="status"></div>
                        <h6 class="fw-bold text-dark mb-1">Menghubungkan ke WhatsApp Server...</h6>
                        <p class="text-muted small mb-0">Kode QR sedang dipersiapkan, harap tunggu beberapa detik...</p>
                    </div>
                `;
            }

            if (isManual && window.Swal) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Status Gateway: ' + (data.connected ? 'Online' : data.status),
                    showConfirmButton: false,
                    timer: 2000
                });
            }

        } catch (err) {
            isConnectedGlobal = false;
            badgeElem.innerHTML = `
                <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                    <span class="pulse-indicator bg-danger"></span>
                    <span class="fw-bold">SERVER GATEWAY OFFLINE</span>
                </span>
            `;
            container.innerHTML = `
                <div class="alert alert-warning border-0 rounded-4 p-4 mb-3" style="background: rgba(245, 158, 11, 0.08);">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Server WhatsApp Gateway Belum Aktif di Port 8088</h6>
                            <p class="text-muted small mb-2">Jalankan file <strong><code>run-wa-gateway.bat</code></strong> di folder utama aplikasi untuk memulai server.</p>
                            <div class="small bg-white p-2.5 rounded-3 border font-monospace text-dark d-inline-block">
                                c:\\xampp\\htdocs\\kepk-ethos\\run-wa-gateway.bat
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    async function triggerResetQr() {
        if (confirm('Buat ulang kode QR dan segarkan koneksi gateway?')) {
            try {
                await fetch(`${GATEWAY_URL}/api/reset`, { method: 'POST' });
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Sesi direset. Kode QR baru segera muncul.',
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
                setTimeout(() => checkGatewayStatusLive(), 1000);
            } catch(e) {
                alert('Gagal mereset gateway: Pastikan server run-wa-gateway.bat sedang berjalan.');
            }
        }
    }

    // Jalankan pengecekan pertama dan polling tiap 3 detik
    checkGatewayStatusLive();
    setInterval(() => {
        if (!isConnectedGlobal) {
            checkGatewayStatusLive();
        }
    }, 3000);
</script>
@endpush
