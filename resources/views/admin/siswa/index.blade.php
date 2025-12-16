@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Siswa</h1>
        <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Tambah Siswa
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            {{-- === FORM PENCARIAN BARU === --}}
            <form action="{{ route('admin.siswa.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Nama, NIP/NIK, atau Email..." value="{{ $keyword ?? '' }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>
            {{-- === AKHIR FORM PENCARIAN === --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th>Nama</th>
                            <th>NIP/NIK</th>
                            <th>Email</th>
                            <th>Unit</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->nip }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->bidang_unit }}</td>
                                <td>
                                <a href="{{ route('admin.siswa.show', $user->id) }}" class="btn btn-sm btn-secondary">Detail</a>
                                <a href="{{ route('admin.siswa.edit', $user->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('admin.siswa.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection