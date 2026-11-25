@extends('layouts.app')

@section('title', 'Status Usulan Proposal')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Status Usulan Protokol Penelitian</h3>
        <p class="text-muted mb-0">Pantau perkembangan proses kelaikan etik riset Anda secara langsung dan terperinci.</p>
    </div>
    <a href="{{ route('peneliti.pengajuan') }}" class="btn btn-primary btn-modern shadow-sm" style="background: linear-gradient(135deg, #0d9488 0%, #0284c7 100%); border: none;">
        <i class="bi bi-plus-lg me-1"></i> Ajukan Proposal Baru
    </a>
</div>

{{-- METRIC STATS SUMMARY --}}
@php
    $totalCount = $proposals->count();
    $menungguCount = $proposals->where('status_id', 1)->count();
    $ditelaahCount = $proposals->whereIn('status_id', [2, 4])->count();
    $revisiCount = $proposals->where('status_id', 3)->count();
    $selesaiCount = $proposals->where('status_id', 5)->count();
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-modern card-hover p-3 border-0 bg-white filter-metric-card" data-status-filter="" style="cursor: pointer;" title="Klik untuk menampilkan semua usulan">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 bg-light-primary text-primary fs-4" style="background: #e0f2fe; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-folder2"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Total Usulan</div>
                    <div class="fs-4 fw-bold text-dark"><span class="stat-counter" data-target="{{ $totalCount }}">0</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-modern card-hover p-3 border-0 bg-white filter-metric-card" data-status-filter="1" style="cursor: pointer;" title="Klik untuk filter: Menunggu Verifikasi">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 text-warning fs-4" style="background: #fef3c7; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Menunggu Verifikasi</div>
                    <div class="fs-4 fw-bold text-warning"><span class="stat-counter" data-target="{{ $menungguCount }}">0</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-modern card-hover p-3 border-0 bg-white filter-metric-card" data-status-filter="3" style="cursor: pointer;" title="Klik untuk filter: Perlu Revisi">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 text-danger fs-4" style="background: #fee2e2; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Perlu Revisi</div>
                    <div class="fs-4 fw-bold text-danger"><span class="stat-counter" data-target="{{ $revisiCount }}">0</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-modern card-hover p-3 border-0 bg-white filter-metric-card" data-status-filter="5" style="cursor: pointer;" title="Klik untuk filter: Lolos Kelaikan">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 text-success fs-4" style="background: #dcfce7; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Lolos Kelaikan</div>
                    <div class="fs-4 fw-bold text-success"><span class="stat-counter" data-target="{{ $selesaiCount }}">0</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($proposals->isEmpty())
    <div class="card card-modern text-center py-5">
        <div class="card-body">
            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background: #f1f5f9;">
                <i class="bi bi-journal-plus text-secondary fs-2"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Belum Ada Pengajuan Proposal</h5>
            <p class="text-muted small mb-4">Anda belum memiliki berkas usulan penelitian yang diajukan ke Komite Etik.</p>
            <a href="{{ route('peneliti.pengajuan') }}" class="btn btn-primary btn-modern px-4" style="background: #0d9488; border: none;">
                Mulai Buat Pengajuan Baru
            </a>
        </div>
    </div>
@else
    {{-- SEARCH BAR CLIENT-SIDE --}}
    <div class="card card-modern p-3 mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="filterProposalInput" class="form-control form-control-modern border-start-0 ps-0" placeholder="Ketik judul penelitian, kode proposal, atau tempat penelitian...">
                </div>
            </div>
            <div class="col-md-4">
                <select id="filterStatusSelect" class="form-select form-control-modern">
                    <option value="">-- Semua Status --</option>
                    <option value="1">Menunggu Verifikasi</option>
                    <option value="2">Sedang Ditelaah</option>
                    <option value="3">Perlu Revisi</option>
                    <option value="4">Sudah Direvisi</option>
                    <option value="5">Lolos (Selesai)</option>
                </select>
            </div>
        </div>
    </div>

    {{-- LIST PROPOSALS DENGAN TIMELINE STEPPER --}}
    <div id="proposalCardsContainer">
        @foreach($proposals as $idx => $p)
            @php
                // Status step definition
                // 1 = Menunggu Verifikasi
                // 2 = Sedang Ditelaah
                // 3 = Perlu Revisi
                // 4 = Sudah Direvisi
                // 5 = Selesai
                $currentStep = match($p->status_id) {
                    1 => 1,
                    2 => 3,
                    3 => 3, // Revisi in step telaah
                    4 => 3,
                    5 => 5,
                    default => 1,
                };
            @endphp
            <div class="card card-modern mb-4 proposal-item" data-status="{{ $p->status_id }}" data-text="{{ strtolower($p->judul_penelitian . ' ' . $p->kode_proposal . ' ' . ($p->tempat_penelitian ?? '')) }}">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold fs-7">
                            <i class="bi bi-upc-scan me-1 text-primary"></i> {{ $p->kode_proposal ?? '-' }}
                        </span>
                        <span class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i> Diajukan: {{ date('d M Y', strtotime($p->tanggal_pengajuan)) }}
                        </span>
                    </div>
                    <div>
                        @if($p->status_id == 1)
                            <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</span>
                        @elseif($p->status_id == 2)
                            <span class="badge bg-info text-dark px-3 py-2"><i class="bi bi-person-workspace me-1"></i> Sedang Ditelaah</span>
                        @elseif($p->status_id == 3)
                            <span class="badge bg-danger px-3 py-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Perlu Revisi Dokumen</span>
                        @elseif($p->status_id == 4)
                            <span class="badge bg-primary px-3 py-2"><i class="bi bi-arrow-repeat me-1"></i> Sudah Mengirim Revisi</span>
                        @elseif($p->status_id == 5)
                            <span class="badge bg-success px-3 py-2"><i class="bi bi-patch-check-fill me-1"></i> Lolos Kelaikan Etik</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">{{ $p->statusProposal?->nama_status ?? $p->status }}</span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row align-items-center mb-4">
                        <div class="col-lg-8">
                            <h5 class="fw-bold text-dark mb-2">{{ $p->judul_penelitian }}</h5>
                            <div class="d-flex align-items-center gap-3 text-muted small flex-wrap">
                                <span><i class="bi bi-tag me-1 text-teal" style="color: #0d9488;"></i> {{ $p->jenisPenelitian?->nama_jenis ?? '-' }}</span>
                                <span><i class="bi bi-geo-alt me-1 text-danger"></i> {{ $p->tempat_penelitian ?? '-' }}</span>
                                @if($p->penugasanReview->count() > 0)
                                    <span><i class="bi bi-people me-1 text-primary"></i> {{ $p->penugasanReview->count() }} Penelaah Ditugaskan</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                                <a href="{{ route('peneliti.detail', $p->id) }}" class="btn btn-outline-primary btn-modern btn-sm">
                                    <i class="bi bi-eye me-1"></i> Detail Usulan
                                </a>

                                @if($p->status_id == 3)
                                    <a href="{{ route('peneliti.revisi', $p->id) }}" class="btn btn-warning btn-modern btn-sm text-dark fw-bold">
                                        <i class="bi bi-upload me-1"></i> Unggah Revisi
                                    </a>
                                @endif

                                @if($p->status_id == 5 && $p->sertifikat)
                                    <a href="{{ route('sertifikat.cetak', $p->id) }}" target="_blank" class="btn btn-success btn-modern btn-sm shadow-xs">
                                        <i class="bi bi-printer me-1"></i> Cetak Sertifikat
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ALERT REVISI KHUSUS --}}
                    @if($p->status_id == 3 && !empty($p->catatan_verifikasi))
                        <div class="alert alert-warning border-0 rounded-3 mb-4 d-flex align-items-start gap-3 p-3">
                            <i class="bi bi-exclamation-octagon-fill fs-4 text-warning"></i>
                            <div class="small">
                                <strong class="d-block text-dark fw-bold mb-1">Catatan Revisi dari Komite Etik:</strong>
                                <p class="mb-2 text-dark">{{ $p->catatan_verifikasi }}</p>
                                <a href="{{ route('peneliti.revisi', $p->id) }}" class="btn btn-xs btn-warning text-dark fw-bold px-3 py-1 rounded">
                                    <i class="bi bi-arrow-up-circle me-1"></i> Buka Halaman Unggah Revisi
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- VISUAL 5-STEP TIMELINE TRACKER --}}
                    <div class="p-3 bg-light rounded-3 mt-2">
                        <div class="d-flex justify-content-between align-items-center position-relative mb-2 px-2" style="z-index: 1;">
                            {{-- Connecting Line --}}
                            <div class="position-absolute start-0 end-0" style="top: 50%; height: 3px; background: #cbd5e1; z-index: 0; margin-left: 20px; margin-right: 20px;"></div>
                            
                            {{-- Line active fill --}}
                            @php
                                $fillPercent = match($p->status_id) {
                                    1 => '10%',
                                    2 => '50%',
                                    3 => '50%',
                                    4 => '50%',
                                    5 => '100%',
                                    default => '0%'
                                };
                                $barColor = ($p->status_id == 3) ? '#f59e0b' : '#0d9488';
                            @endphp
                            <div class="position-absolute start-0" style="top: 50%; height: 3px; width: {{ $fillPercent }}; background: {{ $barColor }}; z-index: 0; margin-left: 20px; transition: width 0.5s ease;"></div>

                            {{-- Step 1: Pengajuan --}}
                            <div class="text-center position-relative" style="z-index: 1;">
                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white shadow-xs" 
                                     style="width: 32px; height: 32px; font-size: 0.8rem; background: #0d9488;">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <span class="d-block mt-1 fw-semibold text-dark" style="font-size: 0.75rem;">1. Pengajuan</span>
                            </div>

                            {{-- Step 2: Verifikasi --}}
                            <div class="text-center position-relative" style="z-index: 1;">
                                @php
                                    $step2Done = $p->status_id >= 2;
                                    $step2Active = $p->status_id == 1;
                                @endphp
                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white shadow-xs" 
                                     style="width: 32px; height: 32px; font-size: 0.8rem; background: {{ $step2Done ? '#0d9488' : ($step2Active ? '#f59e0b' : '#94a3b8') }};">
                                    @if($step2Done)
                                        <i class="bi bi-check-lg"></i>
                                    @elseif($step2Active)
                                        <i class="bi bi-hourglass-split"></i>
                                    @else
                                        2
                                    @endif
                                </div>
                                <span class="d-block mt-1 {{ $step2Active ? 'fw-bold text-warning' : ($step2Done ? 'fw-semibold text-dark' : 'text-muted') }}" style="font-size: 0.75rem;">2. Verifikasi</span>
                            </div>

                            {{-- Step 3: Telaah Etik --}}
                            <div class="text-center position-relative" style="z-index: 1;">
                                @php
                                    $step3Done = $p->status_id == 5;
                                    $step3Active = in_array($p->status_id, [2, 3, 4]);
                                    $isRevisi = $p->status_id == 3;
                                @endphp
                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white shadow-xs" 
                                     style="width: 32px; height: 32px; font-size: 0.8rem; background: {{ $step3Done ? '#0d9488' : ($isRevisi ? '#ef4444' : ($step3Active ? '#0284c7' : '#94a3b8')) }};">
                                    @if($step3Done)
                                        <i class="bi bi-check-lg"></i>
                                    @elseif($isRevisi)
                                        <i class="bi bi-exclamation"></i>
                                    @elseif($step3Active)
                                        <i class="bi bi-arrow-repeat"></i>
                                    @else
                                        3
                                    @endif
                                </div>
                                <span class="d-block mt-1 {{ $step3Active ? ($isRevisi ? 'fw-bold text-danger' : 'fw-bold text-primary') : ($step3Done ? 'fw-semibold text-dark' : 'text-muted') }}" style="font-size: 0.75rem;">
                                    3. {{ $isRevisi ? 'Perlu Revisi' : 'Telaah Etik' }}
                                </span>
                            </div>

                            {{-- Step 4: Keputusan --}}
                            <div class="text-center position-relative" style="z-index: 1;">
                                @php
                                    $step4Done = $p->status_id == 5;
                                @endphp
                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white shadow-xs" 
                                     style="width: 32px; height: 32px; font-size: 0.8rem; background: {{ $step4Done ? '#0d9488' : '#94a3b8' }};">
                                    @if($step4Done)
                                        <i class="bi bi-check-lg"></i>
                                    @else
                                        4
                                    @endif
                                </div>
                                <span class="d-block mt-1 {{ $step4Done ? 'fw-semibold text-dark' : 'text-muted' }}" style="font-size: 0.75rem;">4. Keputusan</span>
                            </div>

                            {{-- Step 5: Sertifikat --}}
                            <div class="text-center position-relative" style="z-index: 1;">
                                @php
                                    $step5Done = ($p->status_id == 5 && $p->sertifikat);
                                @endphp
                                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white shadow-xs" 
                                     style="width: 32px; height: 32px; font-size: 0.8rem; background: {{ $step5Done ? '#10b981' : '#94a3b8' }};">
                                    @if($step5Done)
                                        <i class="bi bi-award-fill"></i>
                                    @else
                                        5
                                    @endif
                                </div>
                                <span class="d-block mt-1 {{ $step5Done ? 'fw-bold text-success' : 'text-muted' }}" style="font-size: 0.75rem;">5. Sertifikat</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('filterProposalInput');
        const statusSelect = document.getElementById('filterStatusSelect');
        const items = document.querySelectorAll('.proposal-item');

        function filterList() {
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const status = statusSelect ? statusSelect.value : '';

            items.forEach(item => {
                const text = item.getAttribute('data-text') || '';
                const itemStatus = item.getAttribute('data-status') || '';

                const matchesQuery = query === '' || text.includes(query);
                const matchesStatus = status === '' || itemStatus === status;

                if (matchesQuery && matchesStatus) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        if (searchInput) searchInput.addEventListener('input', filterList);
        if (statusSelect) statusSelect.addEventListener('change', filterList);

        // Quick click on stat metric cards
        document.querySelectorAll('.filter-metric-card').forEach(card => {
            card.addEventListener('click', function() {
                const st = this.getAttribute('data-status-filter');
                if (statusSelect) {
                    statusSelect.value = st;
                    filterList();
                    // Smooth scroll to container
                    document.getElementById('proposalCardsContainer')?.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    });
</script>
@endpush
