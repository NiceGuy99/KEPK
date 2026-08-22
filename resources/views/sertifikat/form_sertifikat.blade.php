@extends('layouts.app')

@section('title', 'Buat Sertifikat Kelaikan Etik')

@section('content')
<div class="mb-4">
    <a href="{{ route('sekretaris.sertifikat') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Sertifikat</a>
    <h3 class="fw-bold text-dark mb-1">Form Penerbitan Sertifikat Etik</h3>
    <p class="text-muted mb-0">Proposal: <strong>{{ $proposal->judul_penelitian }}</strong></p>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card card-modern p-4">
            <form action="{{ route('sekretaris.sertifikat.simpan', $proposal->id) }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label small fw-medium">Nomor Sertifikat *</label>
                    <input type="text" name="nomor_sertifikat" class="form-control form-control-modern" placeholder="Contoh: KEPK/123/IX/2026" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-medium">Tanggal Terbit *</label>
                    <input type="date" name="tanggal_terbit" class="form-control form-control-modern" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-medium">Catatan / Keterangan Tambahan (Opsional)</label>
                    <textarea name="keterangan" class="form-control form-control-modern" rows="3" placeholder="Catatan khusus penerbitan sertifikat..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-modern w-100 py-2" style="background: #0d9488; border: none;">
                    <i class="bi bi-save me-1"></i> Simpan & Terbitkan Sertifikat
                </button>
            </form>
        </div>
    </div>
</div>
@endsection