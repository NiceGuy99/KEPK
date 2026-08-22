@extends('layouts.app')

@section('title', 'Laporan KEPK')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Laporan Komite Etik Penelitian Kesehatan</h3>
    <p class="text-muted mb-0">Rekapitulasi dan pelaporan berkala kegiatan KEPK RSUD Sidoarjo Barat.</p>
</div>

<!-- FILTER LAPORAN -->
<div class="card card-modern p-3 mb-4">
    <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-bold">Jenis Laporan</label>
            <select name="jenis" class="form-select form-control-modern">
                <option value="pengajuan" {{ $jenis == 'pengajuan' ? 'selected' : '' }}>1. Pengajuan Proposal</option>
                <option value="telaah" {{ $jenis == 'telaah' ? 'selected' : '' }}>2. Penugasan Penelaah</option>
                <option value="selesai" {{ $jenis == 'selesai' ? 'selected' : '' }}>3. Proposal Selesai (Lolos)</option>
                <option value="sertifikat" {{ $jenis == 'sertifikat' ? 'selected' : '' }}>4. Sertifikat Terbit</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold">Dari Tanggal</label>
            <input type="date" name="tgl_awal" class="form-control form-control-modern" value="{{ $tglAwal }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold">Sampai Tanggal</label>
            <input type="date" name="tgl_akhir" class="form-control form-control-modern" value="{{ $tglAkhir }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-modern flex-fill" style="background: #0d9488; border: none;">
                <i class="bi bi-filter"></i> Tampilkan
            </button>
            <a href="{{ route('laporan.cetak', ['jenis' => $jenis, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" target="_blank" class="btn btn-success btn-modern flex-fill">
                <i class="bi bi-printer"></i> Cetak PDF
            </a>
        </div>
    </form>
</div>

<div class="card card-modern">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        @if($jenis == 'pengajuan')
                            <th>Kode Proposal</th>
                            <th>Judul Penelitian</th>
                            <th>Peneliti Utama</th>
                            <th>Jenis Riset</th>
                            <th>Tgl Pengajuan</th>
                        @elseif($jenis == 'telaah')
                            <th>Kode Proposal</th>
                            <th>Judul Penelitian</th>
                            <th>Penelaah Ditugaskan</th>
                            <th>Tgl Penugasan</th>
                            <th>Status Review</th>
                        @elseif($jenis == 'selesai')
                            <th>Kode Proposal</th>
                            <th>Judul Penelitian</th>
                            <th>Peneliti</th>
                            <th>Jenis Persetujuan</th>
                        @elseif($jenis == 'sertifikat')
                            <th>No Sertifikat</th>
                            <th>Kode Proposal</th>
                            <th>Judul Penelitian</th>
                            <th>Peneliti</th>
                            <th>Tgl Terbit</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $idx => $item)
                        <tr>
                            <td class="ps-4 text-muted">{{ method_exists($data, 'firstItem') ? ($data->firstItem() + $idx) : ($idx + 1) }}</td>
                            @if($jenis == 'pengajuan')
                                <td><span class="badge bg-light text-dark border">{{ $item->kode_proposal }}</span></td>
                                <td class="fw-medium text-dark">{{ $item->judul_penelitian }}</td>
                                <td>{{ $item->peneliti?->nama_lengkap }}</td>
                                <td>{{ $item->jenisPenelitian?->nama_jenis }}</td>
                                <td>{{ date('d/m/Y', strtotime($item->tanggal_pengajuan)) }}</td>
                            @elseif($jenis == 'telaah')
                                <td><span class="badge bg-light text-dark border">{{ $item->proposal?->kode_proposal }}</span></td>
                                <td class="fw-medium text-dark">{{ $item->proposal?->judul_penelitian }}</td>
                                <td>{{ $item->penelaah?->nama_lengkap }}</td>
                                <td>{{ date('d/m/Y', strtotime($item->tanggal_penugasan)) }}</td>
                                <td><span class="badge bg-primary">{{ $item->status_review }}</span></td>
                            @elseif($jenis == 'selesai')
                                <td><span class="badge bg-light text-dark border">{{ $item->kode_proposal }}</span></td>
                                <td class="fw-medium text-dark">{{ $item->judul_penelitian }}</td>
                                <td>{{ $item->peneliti?->nama_lengkap }}</td>
                                <td><span class="badge bg-success">{{ $item->jenisPersetujuan?->nama_persetujuan ?? 'Lolos' }}</span></td>
                            @elseif($jenis == 'sertifikat')
                                <td><span class="badge bg-light text-dark border fw-bold">{{ $item->nomor_sertifikat }}</span></td>
                                <td>{{ $item->proposal?->kode_proposal }}</td>
                                <td class="fw-medium text-dark">{{ $item->proposal?->judul_penelitian }}</td>
                                <td>{{ $item->proposal?->peneliti?->nama_lengkap }}</td>
                                <td>{{ date('d/m/Y', strtotime($item->tanggal_terbit)) }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada data laporan untuk kriteria tersebut.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($data, 'hasPages') && $data->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $data->links() }}
        </div>
    @endif
</div>
@endsection
