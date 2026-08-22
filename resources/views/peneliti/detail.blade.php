@extends('layouts.app')

@section('title', 'Detail Proposal')

@section('content')
<div class="mb-4">
    <a href="{{ route('peneliti.status') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Status</a>
    <h3 class="fw-bold text-dark">Detail Usulan Proposal</h3>
    <p class="text-muted mb-0">Kode Proposal: <strong>{{ $proposal->kode_proposal ?? '-' }}</strong></p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-modern p-4 mb-4">
            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-file-earmark-text me-2"></i> Informasi Protokol Penelitian</h5>
            
            <table class="table table-borderless small mb-0">
                <tr>
                    <th width="200" class="text-muted">Judul Penelitian:</th>
                    <td class="fw-semibold text-dark">{{ $proposal->judul_penelitian }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Tempat Penelitian:</th>
                    <td>{{ $proposal->tempat_penelitian ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Jenis Penelitian:</th>
                    <td>{{ $proposal->jenisPenelitian?->nama_jenis ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Tanggal Pengajuan:</th>
                    <td>{{ date('d F Y', strtotime($proposal->tanggal_pengajuan)) }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Status Saat Ini:</th>
                    <td>
                        @if($proposal->status_id == 1)
                            <span class="badge bg-warning text-dark">Menunggu Verifikasi Sekretariat</span>
                        @elseif($proposal->status_id == 2)
                            <span class="badge bg-info text-dark">Sedang Ditelaah oleh Tim Penelaah</span>
                        @elseif($proposal->status_id == 3)
                            <span class="badge bg-danger">Perlu Revisi Dokumen</span>
                        @elseif($proposal->status_id == 4)
                            <span class="badge bg-primary">Sudah Mengirim Revisi</span>
                        @elseif($proposal->status_id == 5)
                            <span class="badge bg-success">Lolos Kelaikan Etik (Selesai)</span>
                        @else
                            <span class="badge bg-secondary">{{ $proposal->statusProposal?->nama_status ?? $proposal->status }}</span>
                        @endif
                    </td>
                </tr>
            </table>

            @if(!empty($proposal->catatan_verifikasi))
                <hr class="my-4">
                <div class="alert alert-warning border-0 rounded-3 mb-0">
                    <h6 class="fw-bold mb-2"><i class="bi bi-chat-left-text me-2"></i> Catatan Verifikasi / Masukan Etik:</h6>
                    <p class="mb-0 small">{{ $proposal->catatan_verifikasi }}</p>
                </div>
            @endif
        </div>

        @if($proposal->status_id == 5 && $proposal->sertifikat)
            <div class="card card-modern p-4 mb-4 border-success border">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h6 class="fw-bold text-success mb-1"><i class="bi bi-award-fill me-2"></i> Sertifikat Ethical Clearance Telah Terbit</h6>
                        <small class="text-muted">No. Sertifikat: <strong>{{ $proposal->sertifikat->nomor_sertifikat }}</strong> | Tgl Terbit: {{ date('d M Y', strtotime($proposal->sertifikat->tanggal_terbit)) }}</small>
                    </div>
                    <a href="{{ route('sertifikat.cetak', $proposal->id) }}" target="_blank" class="btn btn-success btn-modern">
                        <i class="bi bi-printer me-1"></i> Cetak / Unduh Sertifikat
                    </a>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card card-modern p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-folder2-open text-primary me-2"></i> Berkas Terlampir</h6>
            <div class="list-group small">
                @if(!empty($proposal->file_proposal))
                    <a href="{{ asset('uploads/proposal/' . $proposal->file_proposal) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center py-2">
                        <i class="bi bi-file-pdf text-danger fs-5 me-2"></i>
                        <div>
                            <span class="d-block fw-semibold">Proposal Penelitian</span>
                            <small class="text-muted">Klik untuk melihat PDF</small>
                        </div>
                    </a>
                @endif

                @if(!empty($proposal->file_self_assessment))
                    <a href="{{ asset('uploads/proposal/' . $proposal->file_self_assessment) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center py-2">
                        <i class="bi bi-file-pdf text-danger fs-5 me-2"></i>
                        <div>
                            <span class="d-block fw-semibold">Self Assessment</span>
                            <small class="text-muted">Klik untuk melihat PDF</small>
                        </div>
                    </a>
                @endif

                @if(!empty($proposal->file_kuesioner))
                    <a href="{{ asset('uploads/proposal/' . $proposal->file_kuesioner) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center py-2">
                        <i class="bi bi-file-pdf text-danger fs-5 me-2"></i>
                        <div>
                            <span class="d-block fw-semibold">Kuesioner / Instrumen</span>
                            <small class="text-muted">Klik untuk melihat PDF</small>
                        </div>
                    </a>
                @endif

                @if(!empty($proposal->file_lampiran))
                    <a href="{{ asset('uploads/proposal/' . $proposal->file_lampiran) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center py-2">
                        <i class="bi bi-file-pdf text-danger fs-5 me-2"></i>
                        <div>
                            <span class="d-block fw-semibold">Lampiran / Informed Consent</span>
                            <small class="text-muted">Klik untuk melihat PDF</small>
                        </div>
                    </a>
                @endif
            </div>

            @if($proposal->status_id == 3)
                <div class="mt-4">
                    <a href="{{ route('peneliti.revisi', $proposal->id) }}" class="btn btn-warning btn-modern w-100 text-dark fw-semibold">
                        <i class="bi bi-upload me-1"></i> Unggah Berkas Perbaikan
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
