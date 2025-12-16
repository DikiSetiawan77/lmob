@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Detail Siswa: {{ $siswa->name }}</h1>
    <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary mb-3">Kembali ke Daftar</a>

    @if (session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif

    <div class="card">
        <div class="card-header">Dokumen Terunggah</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Jenis Dokumen</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswa->documents as $doc)
                    <tr>
                        <td>{{ ucwords(str_replace('_', ' ', $doc->type)) }}</td>
                        <td>
                            <a href="{{ route('admin.documents.download', $doc) }}">{{ $doc->original_name }}</a>
                        </td>
                        <td>
                             <span class="badge badge-{{ $doc->status == 'verified' ? 'success' : ($doc->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($doc->status) }}
                            </span>
                        </td>
                        <td>
                            @if ($doc->status == 'pending')
                                <form action="{{ route('admin.documents.verify', $doc) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="verified">
                                    <button type="submit" class="btn btn-sm btn-success">Verifikasi</button>
                                </form>
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal{{ $doc->id }}">Tolak</button>
                            @else
                                Diverifikasi oleh Admin pada {{ $doc->verified_at->format('d/m/Y') }}
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Modal Penolakan -->
                    <div class="modal fade" id="rejectModal{{ $doc->id }}"> ... (Isi modal dengan form penolakan) ... </div>
                    @empty
                    <tr><td colspan="4" class="text-center">Siswa ini belum mengunggah dokumen apapun.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection