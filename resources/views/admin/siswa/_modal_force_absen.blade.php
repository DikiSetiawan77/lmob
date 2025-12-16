<!-- Modal Absen Manual -->
<div class="modal fade" id="forceAbsenModal" tabindex="-1" aria-labelledby="forceAbsenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forceAbsenModalLabel">Absenkan Manual untuk: <strong id="modalUserName"></strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.attendance.force') }}" method="POST">
                @csrf
                <div class="modal-body">
                    {{-- Hidden input untuk menyimpan ID user yang akan diabsenkan --}}
                    <input type="hidden" name="user_id" id="modalUserId">

                    <div class="form-group">
                        <label for="check_in_time">Waktu Masuk</label>
                        <input type="datetime-local" name="check_in_time" id="check_in_time" class="form-control" required>
                        <small class="form-text text-muted">Pilih tanggal dan jam absen masuk.</small>
                    </div>

                    <div class="form-group">
                        <label for="note">Catatan (Alasan)</label>
                        <textarea name="note" id="note" class="form-control" rows="3" required placeholder="Contoh: Perangkat user bermasalah"></textarea>
                         <small class="form-text text-muted">Wajib diisi sebagai jejak audit.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                </div>
            </form>
        </div>
    </div>
</div>