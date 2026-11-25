@extends('layouts.app')

@section('title', 'Pengaturan Sertifikat KEPK')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-sliders text-teal me-2" style="color: #0d9488;"></i> Pengaturan Format & Penandatangan Sertifikat
        </h3>
        <p class="text-muted mb-0">Kelola nama penandatangan, NIP, jabatan, kop instansi, dan teks masa berlaku pada sertifikat lolos kaji etik.</p>
    </div>
    <div>
        <button type="submit" form="formPengaturanSertifikat" class="btn btn-primary btn-modern shadow-sm" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none;">
            <i class="bi bi-check2-circle me-1"></i> Simpan Pengaturan
        </button>
    </div>
</div>

@if(session('pesan_sukses'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #ecfdf5; color: #065f46; border-left: 5px solid #10b981 !important;">
        <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
        <span>{{ session('pesan_sukses') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #fef2f2; color: #991b1b; border-left: 5px solid #ef4444 !important;">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
        <strong>Periksa kembali formulir:</strong>
        <ul class="mb-0 mt-1 ps-3">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Kolom Form Pengaturan (Kiri) -->
    <div class="col-lg-7">
        <form id="formPengaturanSertifikat" method="POST" action="{{ route('sekretaris.pengaturan-sertifikat.update') }}">
            @csrf

            <!-- Card 1: Data Penandatangan -->
            <div class="card card-modern shadow-sm border-0 mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-badge-fill text-primary"></i>
                        <span class="fw-bold text-dark">Data Pejabat Penandatangan</span>
                    </div>
                    <small class="text-muted">Data ini dicetak di blok tanda tangan sertifikat dan terekam di dalam QR Code verifikasi.</small>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="nama_penandatangan" class="form-label fw-semibold text-dark">
                            Nama Lengkap & Gelar Penandatangan <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nama_penandatangan" name="nama_penandatangan" 
                               value="{{ old('nama_penandatangan', $pengaturan->nama_penandatangan) }}" 
                               placeholder="Contoh: Dr. dr. Arif Rahman Nurdianto, M.Imun., S.H., M.H." required>
                        <div class="form-text">Nama pejabat yang berwenang menandatangani surat keterangan lolos kaji etik.</div>
                    </div>

                    <div class="mb-3">
                        <label for="jabatan_penandatangan" class="form-label fw-semibold text-dark">
                            Jabatan Penandatangan <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="jabatan_penandatangan" name="jabatan_penandatangan" 
                               value="{{ old('jabatan_penandatangan', $pengaturan->jabatan_penandatangan) }}" 
                               placeholder="Contoh: Ketua KEPK RSUD Sidoarjo Barat" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="pangkat_golongan" class="form-label fw-semibold text-dark">
                                Pangkat / Golongan
                            </label>
                            <input type="text" class="form-control" id="pangkat_golongan" name="pangkat_golongan" 
                                   value="{{ old('pangkat_golongan', $pengaturan->pangkat_golongan) }}" 
                                   placeholder="Contoh: Pembina Tingkat I / IVb">
                        </div>
                        <div class="col-md-6">
                            <label for="nip_penandatangan" class="form-label fw-semibold text-dark">
                                NIP Penandatangan
                            </label>
                            <input type="text" class="form-control" id="nip_penandatangan" name="nip_penandatangan" 
                                   value="{{ old('nip_penandatangan', $pengaturan->nip_penandatangan) }}" 
                                   placeholder="Contoh: 198704162011011007">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Lembaga, Instansi & Kota -->
            <div class="card card-modern shadow-sm border-0 mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-building-fill text-success"></i>
                        <span class="fw-bold text-dark">Kop Lembaga & Instansi</span>
                    </div>
                    <small class="text-muted">Nama komite, institusi rumah sakit, serta kota penerbitan sertifikat.</small>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="nama_lembaga" class="form-label fw-semibold text-dark">
                                Nama Komite (Indonesia) <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama_lembaga" name="nama_lembaga" 
                                   value="{{ old('nama_lembaga', $pengaturan->nama_lembaga) }}" 
                                   placeholder="Contoh: Komite Etik Penelitian Kesehatan" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nama_lembaga_en" class="form-label fw-semibold text-dark">
                                Nama Komite (Inggris)
                            </label>
                            <input type="text" class="form-control" id="nama_lembaga_en" name="nama_lembaga_en" 
                                   value="{{ old('nama_lembaga_en', $pengaturan->nama_lembaga_en) }}" 
                                   placeholder="Contoh: Health Research Ethics Committee">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_instansi" class="form-label fw-semibold text-dark">
                            Nama Instansi / Rumah Sakit <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nama_instansi" name="nama_instansi" 
                               value="{{ old('nama_instansi', $pengaturan->nama_instansi) }}" 
                               placeholder="Contoh: Rumah Sakit Umum Daerah Sidoarjo Barat" required>
                    </div>

                    <div class="mb-0">
                        <label for="kota_penerbitan" class="form-label fw-semibold text-dark">
                            Kota Penerbitan <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="kota_penerbitan" name="kota_penerbitan" 
                               value="{{ old('kota_penerbitan', $pengaturan->kota_penerbitan) }}" 
                               placeholder="Contoh: Sidoarjo" required>
                        <div class="form-text">Digunakan pada tanggal pengesahan tanda tangan (misal: <em>Sidoarjo, 24 November 2026</em>).</div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Masa Berlaku / Footer Note -->
            <div class="card card-modern shadow-sm border-0 mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-warning"></i>
                        <span class="fw-bold text-dark">Catatan Kaki Masa Berlaku</span>
                    </div>
                    <small class="text-muted">Keterangan masa aktif sertifikat di bagian bawah lembar.</small>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="masa_berlaku_teks" class="form-label fw-semibold text-dark">
                                Keterangan Masa Berlaku (Indonesia) <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="masa_berlaku_teks" name="masa_berlaku_teks" 
                                   value="{{ old('masa_berlaku_teks', $pengaturan->masa_berlaku_teks) }}" 
                                   placeholder="Contoh: *Masa berlaku 1 tahun" required>
                        </div>
                        <div class="col-md-6">
                            <label for="masa_berlaku_teks_en" class="form-label fw-semibold text-dark">
                                Keterangan Masa Berlaku (Inggris)
                            </label>
                            <input type="text" class="form-control" id="masa_berlaku_teks_en" name="masa_berlaku_teks_en" 
                                   value="{{ old('masa_berlaku_teks_en', $pengaturan->masa_berlaku_teks_en) }}" 
                                   placeholder="Contoh: 1 year validity period">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-4">
                <button type="submit" class="btn btn-primary btn-modern shadow px-4 py-2" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none;">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Sertifikat
                </button>
            </div>
        </form>
    </div>

    <!-- Kolom Pratinjau Langsung (Kanan) -->
    <div class="col-lg-5">
        <div class="position-sticky" style="top: 80px;">
            <div class="card card-modern shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-eye-fill text-teal" style="color: #0d9488;"></i>
                        <span class="fw-bold text-dark">Pratinjau Langsung (Live Preview)</span>
                    </div>
                    <span class="badge bg-light text-primary border">Real-time</span>
                </div>
                <div class="card-body p-4" style="background: #f8fafc;">
                    <div class="alert alert-info py-2 px-3 small border-0 mb-3" style="background: #e0f2fe; color: #0369a1; border-radius: 8px;">
                        <i class="bi bi-info-circle me-1"></i> Tampilan di bawah akan otomatis terupdate saat Anda mengetik di formulir.
                    </div>

                    <!-- Miniatur Lembar Sertifikat -->
                    <div class="p-3 bg-white shadow-sm border text-center" style="border-radius: 8px; font-family: 'Times New Roman', Times, serif; font-size: 11px; line-height: 1.35; color: #1e293b;">
                        <!-- Kop Mini -->
                        <div class="mb-2">
                            <div class="fw-bold fs-7 text-uppercase" id="prev_nama_lembaga" style="font-family: Arial, sans-serif; letter-spacing: 0.5px;">
                                {{ $pengaturan->nama_lembaga }}
                            </div>
                            <div class="fst-italic text-muted" id="prev_nama_lembaga_en" style="font-size: 9.5px; font-family: Arial, sans-serif;">
                                {{ $pengaturan->nama_lembaga_en }}
                            </div>
                            <div class="fw-bold text-dark" id="prev_nama_instansi" style="font-size: 10px; font-family: Arial, sans-serif;">
                                {{ $pengaturan->nama_instansi }}
                            </div>
                        </div>
                        <hr class="my-2" style="border-top: 1.5px solid #1e3a8a; opacity: 1;">

                        <!-- Judul Sertifikat -->
                        <div class="my-2">
                            <strong class="d-block" style="font-size: 12px; color: #1e3a8a;">Keterangan Lolos Kaji Etik</strong>
                            <span class="fst-italic text-muted" style="font-size: 9px;">Description of Ethical Approval</span>
                            <div class="fw-bold mt-1" style="font-size: 10px; letter-spacing: 1px;">&ldquo;ETHICAL APPROVAL&rdquo;</div>
                            <div class="text-muted" style="font-size: 9.5px;">NO : 00001/KEPKRSUDSIBAR/{{ date('m/Y') }}</div>
                        </div>

                        <!-- Blok Tanda Tangan Mini -->
                        <div class="text-end mt-3 pt-2" style="font-family: Arial, sans-serif; font-size: 9.5px;">
                            <div class="d-inline-block text-start" style="width: 200px;">
                                <div id="prev_tanggal">
                                    <span id="prev_kota">{{ $pengaturan->kota_penerbitan }}</span>, {{ date('d') }} {{ ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'][date('m')] }} {{ date('Y') }}
                                </div>
                                <div class="fw-semibold text-secondary" id="prev_jabatan">
                                    {{ $pengaturan->jabatan_penandatangan }}
                                </div>

                                <!-- QR Code Box Mockup -->
                                <div class="my-2 p-1 border rounded bg-white d-flex align-items-center gap-2" style="border-color: #cbd5e1 !important;">
                                    <div style="width: 48px; height: 48px; min-width: 48px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                        <i class="bi bi-qr-code text-dark" style="font-size: 32px;"></i>
                                    </div>
                                    <div style="font-size: 7.5px; line-height: 1.2; color: #475569; text-align: left;">
                                        <strong class="text-primary d-block" style="font-size: 8px;">Ditandatangani Elektronik</strong>
                                        Scan verifikasi QR
                                    </div>
                                </div>

                                <div class="fw-bold text-dark" id="prev_nama_penandatangan" style="font-size: 10px;">
                                    {{ $pengaturan->nama_penandatangan }}
                                </div>
                                <div class="text-muted" id="prev_pangkat_golongan" style="font-size: 9px;">
                                    {{ $pengaturan->pangkat_golongan }}
                                </div>
                                <div class="text-muted" id="prev_nip" style="font-size: 9px;">
                                    NIP {{ $pengaturan->nip_penandatangan }}
                                </div>
                            </div>
                        </div>

                        <!-- Footer Masa Berlaku Mini -->
                        <div class="text-start mt-3 pt-2 border-top text-muted" style="font-size: 8.5px; font-style: italic;">
                            <div id="prev_masa_berlaku">{{ $pengaturan->masa_berlaku_teks }}</div>
                            <div id="prev_masa_berlaku_en">{{ $pengaturan->masa_berlaku_teks_en }}</div>
                        </div>
                    </div>

                    <!-- Keterangan Tambahan -->
                    <div class="mt-3 p-3 bg-white rounded border small text-muted">
                        <strong class="text-dark d-block mb-1"><i class="bi bi-shield-check text-success me-1"></i> Sinkronisasi QR Code Otomatis</strong>
                        Setiap kali data nama pejabat, NIP, atau pangkat diperbarui di sini, QR Code verifikasi pada sertifikat akan langsung memuat data penandatangan yang baru tanpa perlu konfigurasi ulang.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Live Preview --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fields = [
        { input: 'nama_penandatangan', target: 'prev_nama_penandatangan', prefix: '', default: 'Dr. dr. Arif Rahman Nurdianto, M.Imun., S.H., M.H.' },
        { input: 'jabatan_penandatangan', target: 'prev_jabatan', prefix: '', default: 'Ketua KEPK RSUD Sidoarjo Barat' },
        { input: 'pangkat_golongan', target: 'prev_pangkat_golongan', prefix: '', default: 'Pembina Tingkat I / IVb' },
        { input: 'nip_penandatangan', target: 'prev_nip', prefix: 'NIP ', default: '198704162011011007' },
        { input: 'kota_penerbitan', target: 'prev_kota', prefix: '', default: 'Sidoarjo' },
        { input: 'nama_lembaga', target: 'prev_nama_lembaga', prefix: '', default: 'Komite Etik Penelitian Kesehatan' },
        { input: 'nama_lembaga_en', target: 'prev_nama_lembaga_en', prefix: '', default: 'Health Research Ethics Committee' },
        { input: 'nama_instansi', target: 'prev_nama_instansi', prefix: '', default: 'Rumah Sakit Umum Daerah Sidoarjo Barat' },
        { input: 'masa_berlaku_teks', target: 'prev_masa_berlaku', prefix: '', default: '*Masa berlaku 1 tahun' },
        { input: 'masa_berlaku_teks_en', target: 'prev_masa_berlaku_en', prefix: '', default: '1 year validity period' }
    ];

    fields.forEach(f => {
        const inputElem = document.getElementById(f.input);
        const targetElem = document.getElementById(f.target);

        if (inputElem && targetElem) {
            inputElem.addEventListener('input', function () {
                const val = this.value.trim();
                targetElem.textContent = val ? (f.prefix + val) : f.default;
            });
        }
    });
});
</script>
@endsection
