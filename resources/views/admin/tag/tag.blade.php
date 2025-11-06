@extends('admin.layouts.app')

@section('content')

    <head>
        <link rel="stylesheet" href="{{ asset('admin/css/tag/tag.css') }}">
    </head>

    <div class="content" :class="darkMode ? 'table-dark' : 'table-light'">
        <!-- Tombol Kontrol -->
        <div class="row align-items-center mb-3">
            <!-- Kiri -->
            <div class="col-md-2">
                <div class="table-controls">
                    <a href="{{ route('admin.tag.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Tag
                    </a>
                </div>
            </div>

            <!-- Kanan -->
            <div class="col-md-10">
                <div class="table-controls d-flex justify-content-end gap-2">
                    <!-- Download -->
                    <a href="{{ route('admin.tag.downloadTemplate') }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Download Template Tag Excel
                    </a>

                    <!-- Import -->
                    <form action="{{ route('admin.tag.import') }}" method="POST" enctype="multipart/form-data"
                        class="d-flex align-items-center gap-2">
                        @csrf

                        <label for="" class="custom-file">
                        <input type="file" class="form-control" name="file" class="form-control" required>
                        </label>

                        <button type="submit" class="btn btn-warning text-center button-import" >
                            <i class="fas fa-file-import"></i> Import Data Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <!-- Tabel -->
        <table id="userTable" class="display w-full border-collapse">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Tag</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tag as $t)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $t->nama_tag }}</td>
                        <td>
                            <div class="action-buttons flex gap-2">
                                <form action="{{ Route('admin.tag.destroy', $t->id) }}" method="POST"
                                    id="form-delete-alert-{{ $t->id }}">
                                    @csrf
                                    <button class="btn btn-sm btn-danger btn-delete-permanent" data-id-alert-="{{ $t->id }}"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="{{ asset('admin/js/user.js') }}"></script>
@endsection
