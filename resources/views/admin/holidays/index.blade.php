@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header"><h4>Tambah Hari Libur</h4></div>
                <div class="card-body">
                    @if($errors->any())<div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                    <form action="{{ route('admin.holidays.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3"><label>Tanggal</label><input type="date" name="date" class="form-control" required></div>
                        <div class="form-group mb-3"><label>Nama Hari Libur</label><input type="text" name="name" class="form-control" required placeholder="Contoh: Hari Kemerdekaan"></div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
            <div class="card shadow-sm">
                <div class="card-header"><h4>Daftar Hari Libur</h4></div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead><tr><th>Tanggal</th><th>Nama</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @forelse ($holidays as $holiday)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($holiday->date)->isoFormat('dddd, D MMMM Y') }}</td>
                                <td>{{ $holiday->name }}</td>
                                <td>
                                    <form action="{{ route('admin.holidays.destroy', $holiday->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus hari libur ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center">Belum ada data hari libur.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $holidays->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection