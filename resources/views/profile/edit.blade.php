@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1>Profil & Dokumen Saya</h1>
            <p class="text-muted">Lengkapi data diri Anda dan unggah dokumen yang diperlukan.</p>

            {{-- Menampilkan error umum --}}
            @if ($errors->any() && !$errors->hasBag('updatePassword'))
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- === FORM EDIT BIODATA DITAMBAHKAN DI SINI === --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white"><h5 class="mb-0">Biodata Siswa</h5></div>
                <div class="card-body">
                    @if (session('status_profile'))
                        <div class="alert alert-success">{{ session('status_profile') }}</div>
                    @endif
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>NIP</label>
                                <input type="text" name="nip" class="form-control" value="{{ old('nip', $user->nip) }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Bidang / Unit</label>
                       <input type="text" name="bidang_unit" class="form-control" value="{{ old('bidang_unit', $user->bidang_unit) }}">
                </div>
                     </div>
                         <div class="form-group mb-3">
                            <label>Instansi</label>
                            <input type="text" class="form-control" value="{{ $user->instansi }}" disabled>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan Biodata</button>
                    </form>
                </div>
            </div>

            {{-- === FORM UBAH PASSWORD DITAMBAHKAN DI SINI === --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning"><h5 class="mb-0">Ubah Password</h5></div>
                <div class="card-body">
                    @if (session('status_password'))
                        <div class="alert alert-success">{{ session('status_password') }}</div>
                    @endif
                     @if ($errors->updatePassword->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->updatePassword->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="current_password">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password Baru</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Update Password</button>
                    </form>
                </div>
            </div>

            {{-- Panel Upload Dokumen (Kode Asli Anda) --}}
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Upload Dokumen Wajib</h5>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <p>Pastikan file dalam format PDF, JPG, atau PNG dan ukuran tidak lebih dari 2MB.</p>
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 30%;">Jenis Dokumen</th>
                                <th style="width: 20%;">Status</th>
                                <th style="width: 50%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documentTypes as $type)
                            <tr>
                                <td class="align-middle font-weight-bold">{{ ucwords(str_replace('_', ' ', $type)) }}</td>
                                <td class="align-middle text-center">
                                    @if(isset($documents[$type]))
                                        @php $doc = $documents[$type]; @endphp
                                        <span class="badge badge-{{ ['pending' => 'warning', 'verified' => 'success', 'rejected' => 'danger'][$doc->status] ?? 'secondary' }} p-2">
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                        @if($doc->status == 'rejected' && $doc->rejection_note)
                                            <small class="d-block text-danger mt-1">Catatan: {{ $doc->rejection_note }}</small>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary p-2">Belum Diunggah</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($documents[$type]))
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>{{ Str::limit($doc->original_name, 25) }}</span>
                                            <div>
                                                <a href="{{ route('profile.documents.download', $doc->id) }}" class="btn btn-sm btn-info" title="Download"><i class="fas fa-download"></i></a>
                                                @if($doc->status != 'verified')
                                                <form action="{{ route('profile.documents.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus dokumen ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                    <form action="{{ route('profile.documents.upload') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ $type }}">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="document_file" class="custom-file-input" id="doc_{{ $type }}" required>
                                                <label class="custom-file-label" for="doc_{{ $type }}">Pilih file...</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-primary"><i class="fas fa-upload"></i> Upload</button>
                                            </div>
                                        </div>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk menampilkan nama file di input file Bootstrap
    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function(e) {
            var fileName = e.target.files.length > 0 ? e.target.files[0].name : 'Pilih file...';
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    });
</script>
@endpush