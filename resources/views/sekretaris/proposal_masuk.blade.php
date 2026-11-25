@extends('layouts.app')

@section('title', 'Proposal Masuk')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Proposal Usulan Masuk</h3>
        <p class="text-muted mb-0">Kelola berkas usulan penelitian, lakukan verifikasi administrasi, dan tugaskan penelaah etik.</p>
    </div>
</div>

<!-- QUICK FILTER BADGES & SEARCH -->
<div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
    <span class="text-muted small fw-semibold me-1"><i class="bi bi-funnel-fill text-primary me-1"></i> Filter Cepat:</span>
    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill quick-status-pill active" data-status="">Semua Usulan</button>
    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill quick-status-pill" data-status="1"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</button>
    <button type="button" class="btn btn-sm btn-outline-info rounded-pill quick-status-pill" data-status="2"><i class="bi bi-search me-1"></i> Sedang Ditelaah</button>
    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill quick-status-pill" data-status="3"><i class="bi bi-exclamation-octagon me-1"></i> Perlu Revisi</button>
    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill quick-status-pill" data-status="4"><i class="bi bi-arrow-repeat me-1"></i> Sudah Direvisi</button>
    <button type="button" class="btn btn-sm btn-outline-success rounded-pill quick-status-pill" data-status="5"><i class="bi bi-patch-check-fill me-1"></i> Selesai</button>
</div>

<div class="card card-modern p-3 mb-4">
    <form method="GET" action="{{ route('sekretaris.proposal-masuk') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="liveSearchProposal" name="search" class="form-control form-control-modern border-start-0 ps-0" placeholder="Ketik langsung untuk mencari judul, kode, peneliti, instansi..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="status_id" id="statusFilterSelect" class="form-select form-control-modern">
                <option value="">-- Semua Status Proposal --</option>
                @foreach($masterStatus as $ms)
                    <option value="{{ $ms->id }}" {{ request('status_id') == $ms->id ? 'selected' : '' }}>
                        {{ $ms->nama_status }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-modern flex-fill" style="background: #0d9488; border: none;">
                <i class="bi bi-filter me-1"></i> Terapkan
            </button>
            @if(request()->hasAny(['search', 'status_id']))
                <a href="{{ route('sekretaris.proposal-masuk') }}" class="btn btn-light btn-modern border">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

<div class="card card-modern">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-inboxes text-primary me-2"></i> Daftar Berkas Masuk (<span id="visibleProposalCount">{{ $proposals->total() }}</span> Berkas)</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="proposalTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Kode</th>
                        <th>Peneliti & Asal Kampus</th>
                        <th>Judul Penelitian</th>
                        <th>Tgl Masuk</th>
                        <th>Status</th>
                        <th class="text-center pe-4" style="min-width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="proposalTableBody">
                    @forelse($proposals as $idx => $p)
                        <tr class="proposal-table-row" data-status="{{ $p->status_id }}" data-search="{{ strtolower($p->kode_proposal . ' ' . ($p->peneliti?->nama_lengkap ?? '') . ' ' . ($p->peneliti?->profil?->asal_kampus ?? '') . ' ' . $p->judul_penelitian) }}">
                            <td class="ps-4 text-muted">{{ $proposals->firstItem() + $idx }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $p->kode_proposal ?? '-' }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted"><i class="bi bi-building me-1"></i>{{ $p->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($p->judul_penelitian, 65) }}</div>
                                <small class="text-muted">{{ $p->jenisPenelitian?->nama_jenis ?? '-' }}</small>
                            </td>
                            <td>{{ date('d/m/Y', strtotime($p->tanggal_pengajuan)) }}</td>
                            <td>
                                @if($p->status_id == 1)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</span>
                                @elseif($p->status_id == 2)
                                    <span class="badge bg-info text-dark"><i class="bi bi-person-workspace me-1"></i> Ditelaah</span>
                                @elseif($p->status_id == 3)
                                    <span class="badge bg-danger"><i class="bi bi-exclamation-circle me-1"></i> Perlu Revisi</span>
                                @elseif($p->status_id == 4)
                                    <span class="badge bg-primary"><i class="bi bi-arrow-repeat me-1"></i> Direvisi</span>
                                @elseif($p->status_id == 5)
                                    <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Selesai</span>
                                @else
                                    <span class="badge bg-secondary">{{ $p->statusProposal?->nama_status ?? $p->status }}</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center align-items-center gap-1 flex-wrap">
                                    <a href="{{ route('sekretaris.verifikasi', $p->id) }}" class="btn btn-sm btn-outline-primary btn-modern" title="Verifikasi Berkas">
                                        <i class="bi bi-shield-check me-1"></i> Verifikasi
                                    </a>

                                    <button type="button" class="btn btn-sm btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#modalTerima{{ $p->id }}" style="background: #0d9488; border: none;" title="Tugaskan Penelaah">
                                        <i class="bi bi-person-plus me-1"></i> Tugaskan
                                    </button>
                                </div>

                                <!-- Modal Terima & Pilih Penelaah -->
                                <div class="modal fade text-start" id="modalTerima{{ $p->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 16px;">
                                            <form action="{{ route('sekretaris.tugas.assign') }}" method="POST" class="form-assign">
                                                @csrf
                                                <input type="hidden" name="proposal_id" value="{{ $p->id }}">
                                                
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold"><i class="bi bi-person-check-fill text-primary me-2"></i> Penugasan Penelaah</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-2">
                                                        <h6 class="fw-bold text-dark mb-1">{{ Str::limit($p->judul_penelitian, 70) }}</h6>
                                                        <small class="text-muted">Oleh: {{ $p->peneliti?->nama_lengkap }}</small>
                                                    </div>
                                                    <hr>
                                                    
                                                    <!-- DROPDOWN & KOTAK PENELAAH TERPILIH -->
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-dark">Pilih Penelaah (1 - 5 Orang) *</label>
                                                        
                                                        <!-- Dropdown Sumber Pilihan -->
                                                        <select class="form-select form-control-modern dropdown-master mb-2">
                                                            <option value="">-- Pilih dan Klik Penelaah --</option>
                                                            @foreach($calonPenelaah as $cp)
                                                                <option value="{{ $cp->id }}" data-name="{{ $cp->nama_lengkap }} ({{ $cp->role?->nama_role ?? 'Penelaah' }})">
                                                                    {{ $cp->nama_lengkap }} ({{ $cp->role?->nama_role ?? 'Penelaah' }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted d-block mb-2">Pilih nama dari dropdown di atas untuk menambahkannya ke daftar penugasan.</small>

                                                        <!-- Kotak Penampung Badge/Tag Penelaah Terpilih -->
                                                        <div class="border rounded p-2 bg-light container-terpilih" style="min-height: 80px; max-height: 140px; overflow-y: auto;">
                                                            <span class="text-muted small placeholder-text">Belum ada penelaah yang dipilih.</span>
                                                        </div>
                                                        <div class="text-danger small mt-1 d-none alert-limit">Maksimal hanya 5 penelaah yang dapat dipilih!</div>
                                                    </div>

                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <label class="form-label small fw-medium">Waktu Mulai *</label>
                                                            <input type="date" name="tanggal_mulai" class="form-control form-control-modern" value="{{ date('Y-m-d') }}" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label small fw-medium">Waktu Selesai *</label>
                                                            <input type="date" name="tanggal_selesai" class="form-control form-control-modern" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light border btn-modern" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary btn-modern" style="background: #0d9488; border: none;">Simpan Penugasan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Tidak ditemukan proposal yang cocok dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($proposals->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $proposals->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const forms = document.querySelectorAll('.form-assign');

        forms.forEach(form => {
            const dropdownMaster = form.querySelector('.dropdown-master');
            const containerTerpilih = form.querySelector('.container-terpilih');
            const placeholderText = containerTerpilih.querySelector('.placeholder-text');
            const alertLimit = form.querySelector('.alert-limit');

            dropdownMaster.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const userId = selectedOption.value;
                const userName = selectedOption.getAttribute('data-name');

                if (!userId) return;

                const existingInput = form.querySelector(`input[name="penelaah_ids[]"][value="${userId}"]`);
                if (existingInput) {
                    showToast('warning', 'Penelaah ini sudah ditambahkan.');
                    this.value = "";
                    return;
                }

                const currentCount = form.querySelectorAll('input[name="penelaah_ids[]"]').length;
                if (currentCount >= 5) {
                    showToast('warning', 'Maksimal hanya 5 penelaah yang dapat dipilih!');
                    this.value = "";
                    alertLimit.classList.remove('d-none');
                    return;
                }

                if (placeholderText) {
                    placeholderText.style.display = 'none';
                }

                const badge = document.createElement('div');
                badge.className = 'badge text-white d-flex justify-content-between align-items-center p-2 mb-1 rounded';
                badge.style.background = '#0d9488';
                badge.innerHTML = `
                    <span>${userName}</span>
                    <button type="button" class="btn-close btn-close-white btn-sm ms-2" style="font-size: 0.65rem;"></button>
                    <input type="hidden" name="penelaah_ids[]" value="${userId}">
                `;

                badge.querySelector('.btn-close').addEventListener('click', function() {
                    badge.remove();
                    if (form.querySelectorAll('input[name="penelaah_ids[]"]').length === 0 && placeholderText) {
                        placeholderText.style.display = 'inline';
                    }
                    alertLimit.classList.add('d-none');
                });

                containerTerpilih.appendChild(badge);
                this.value = "";
                alertLimit.classList.add('d-none');
            });

            form.addEventListener('submit', function(e) {
                const selectedCount = form.querySelectorAll('input[name="penelaah_ids[]"]').length;
                if (selectedCount === 0) {
                    e.preventDefault();
                    showToast('error', 'Harap pilih minimal 1 penelaah sebelum menyimpan!');
                }
            });
        });

        // --- Client-side Live Search & Quick Filter Pills ---
        const liveSearchInput = document.getElementById('liveSearchProposal');
        const quickPills = document.querySelectorAll('.quick-status-pill');
        const rows = document.querySelectorAll('.proposal-table-row');
        const visibleCounter = document.getElementById('visibleProposalCount');
        let activeStatus = '';

        function applyTableFilters() {
            const query = liveSearchInput ? liveSearchInput.value.toLowerCase().trim() : '';
            let visible = 0;

            rows.forEach(row => {
                const text = row.getAttribute('data-search') || row.textContent.toLowerCase();
                const status = row.getAttribute('data-status') || '';

                const matchesQuery = !query || text.includes(query);
                const matchesStatus = !activeStatus || status === activeStatus;

                if (matchesQuery && matchesStatus) {
                    row.classList.remove('table-row-hidden');
                    visible++;
                } else {
                    row.classList.add('table-row-hidden');
                }
            });

            if (visibleCounter) {
                visibleCounter.textContent = visible;
            }
        }

        if (liveSearchInput) {
            liveSearchInput.addEventListener('input', applyTableFilters);
        }

        quickPills.forEach(pill => {
            pill.addEventListener('click', function() {
                quickPills.forEach(p => p.classList.remove('active', 'btn-primary', 'text-white'));
                this.classList.add('active');
                activeStatus = this.getAttribute('data-status') || '';
                applyTableFilters();
            });
        });
    });
</script>
@endpush