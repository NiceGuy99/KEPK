<!-- MODAL GANTI PASSWORD -->
<div class="modal fade" id="modalGantiPassword" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
            <form action="{{ route('password.change') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-key text-primary me-2"></i> Ganti Kata Sandi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-muted">Kata Sandi Saat Ini</label>
                        <input type="password" name="password_lama" class="form-control form-control-modern" required placeholder="Masukkan sandi lama...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium text-muted">Kata Sandi Baru</label>
                        <input type="password" name="password_baru" class="form-control form-control-modern" required placeholder="Buat sandi baru (min. 5 karakter)..." minlength="5">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-medium text-muted">Ulangi Kata Sandi Baru</label>
                        <input type="password" name="konfirmasi_password" class="form-control form-control-modern" required placeholder="Ketik ulang sandi baru...">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary btn-modern" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-modern" style="background: #0d9488; border: none;">Simpan Sandi Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>
