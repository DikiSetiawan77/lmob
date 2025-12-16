@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>Form Pengajuan Cuti / Sakit</h4></div>
                <div class="card-body">
                    <form action="{{ route('leave.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3"><label>Jenis Pengajuan</label><select name="type" class="form-control" required><option value="cuti">Cuti Tahunan</option><option value="sakit">Izin Sakit</option></select></div>
                        <div class="row"><div class="col-md-6 form-group mb-3"><label>Tanggal Mulai</label><input type="date" name="start_date" class="form-control" required></div><div class="col-md-6 form-group mb-3"><label>Tanggal Selesai</label><input type="date" name="end_date" class="form-control" required></div></div>
                        <div class="form-group mb-3"><label>Alasan / Keterangan</label><textarea name="reason" rows="4" class="form-control" required></textarea></div>
                        <div class="form-group mb-3"><label>Lampiran (Contoh: Surat Dokter)</label><input type="file" name="attachment" class="form-control"></div>
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection