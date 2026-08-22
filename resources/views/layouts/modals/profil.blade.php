<div class="modal fade" id="modalLihatProfil" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-badge text-primary me-2"></i> Informasi Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 80px; height: 80px; background: var(--primary-gradient);">
                    <i class="bi bi-person-fill" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ Auth::user()?->nama_lengkap ?? 'Pengguna' }}</h5>
                <span class="badge bg-light text-primary border px-3 py-1 mb-3">Hak Akses: {{ ucfirst(Auth::user()?->role_name ?? '') }}</span>

                <div class="text-start bg-light p-3 rounded-3 small">
                    <p class="mb-1"><strong>Email:</strong> {{ Auth::user()?->email }}</p>
                    <p class="mb-1"><strong>NIK:</strong> {{ Auth::user()?->profil?->nik ?? '-' }}</p>
                    <p class="mb-1"><strong>No. Telepon:</strong> {{ Auth::user()?->profil?->nomor_telepon ?? '-' }}</p>
                    <p class="mb-1"><strong>Asal Instansi:</strong> {{ Auth::user()?->profil?->asal_kampus ?? '-' }}</p>
                    <p class="mb-0"><strong>Status Pegawai/Mhs:</strong> {{ Auth::user()?->profil?->statusPengguna?->nama_status ?? '-' }}</p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4 pt-0">
                <button type="button" class="btn btn-light btn-modern w-100" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
