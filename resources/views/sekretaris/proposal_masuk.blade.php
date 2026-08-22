@extends('layouts.app')

@section('title', 'Proposal Masuk')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Proposal Usulan Masuk</h3>
    <p class="text-muted mb-0">Kelola dan tugaskan penelaah untuk berkas usulan penelitian yang masuk.</p>
</div>

<div class="card card-modern">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-inboxes text-primary me-2"></i> Daftar Proposal Menunggu Tindakan</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Kode</th>
                        <th>Peneliti & Kampus</th>
                        <th>Judul Penelitian</th>
                        <th>Tgl Masuk</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals->where('status_id', 1) as $idx => $p)
                        <tr>
                            <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $p->kode_proposal ?? '-' }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->peneliti?->nama_lengkap ?? '-' }}</div>
                                <small class="text-muted">{{ $p->peneliti?->profil?->asal_kampus ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ Str::limit($p->judul_penelitian, 60) }}</div>
                                <small class="text-muted">{{ $p->jenisPenelitian?->nama_jenis ?? '-' }}</small>
                            </td>
                            <td>{{ date('d/m/Y', strtotime($p->tanggal_pengajuan)) }}</td>
                            <td class="text-center pe-4">
                                <button type="button" class="btn btn-sm btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#modalTerima{{ $p->id }}" style="background: #0d9488; border: none;">
                                    <i class="bi bi-check-circle me-1"></i> Terima & Tugaskan
                                </button>

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
                                                    <button type="submit" class="btn btn-primary btn-modern" style="background: #0d9488; border: none;">Terima & Simpan</button>
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
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada proposal baru yang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
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

            // Event saat pilihan dropdown diubah
            dropdownMaster.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const userId = selectedOption.value;
                const userName = selectedOption.getAttribute('data-name');

                if (!userId) return;

                // Cek apakah penelaah sudah pernah dipilih sebelumnya
                const existingInput = form.querySelector(`input[name="penelaah_ids[]"][value="${userId}"]`);
                if (existingInput) {
                    alert('Penelaah ini sudah ditambahkan.');
                    this.value = "";
                    return;
                }

                // Cek batasan maksimal 5 orang
                const currentCount = form.querySelectorAll('input[name="penelaah_ids[]"]').length;
                if (currentCount >= 5) {
                    alert('Maksimal hanya 5 penelaah yang dapat dipilih!');
                    this.value = "";
                    alertLimit.classList.remove('d-none');
                    return;
                }

                // Sembunyikan tulisan placeholder jika ada
                if (placeholderText) {
                    placeholderText.style.display = 'none';
                }

                // Buat elemen Badge / Tag untuk ditampilkan di bawah
                const badge = document.createElement('div');
                badge.className = 'badge bg-teal text-white d-flex justify-content-between align-items-center p-2 mb-1 rounded';
                badge.style.background = '#0d9488';
                badge.innerHTML = `
                    <span>${userName}</span>
                    <button type="button" class="btn-close btn-close-white btn-sm ms-2" style="font-size: 0.65rem;"></button>
                    <input type="hidden" name="penelaah_ids[]" value="${userId}">
                `;

                // Tombol Hapus Tag
                badge.querySelector('.btn-close').addEventListener('click', function() {
                    badge.remove();
                    // Munculkan kembali placeholder jika kosong
                    if (form.querySelectorAll('input[name="penelaah_ids[]"]').length === 0 && placeholderText) {
                        placeholderText.style.display = 'inline';
                    }
                    alertLimit.classList.add('d-none');
                });

                containerTerpilih.appendChild(badge);
                this.value = ""; // Reset dropdown kembali ke default
                alertLimit.classList.add('d-none');
            });

            // Validasi minimal 1 penelaah dipilih saat submit form
            form.addEventListener('submit', function(e) {
                const selectedCount = form.querySelectorAll('input[name="penelaah_ids[]"]').length;
                if (selectedCount === 0) {
                    e.preventDefault();
                    alert('Harap pilih minimal 1 penelaah!');
                }
            });
        });
    });
</script>
@endpush