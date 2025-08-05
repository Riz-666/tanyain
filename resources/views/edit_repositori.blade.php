@extends('components.app')

@section('body')
    <div class="container mt-4">
        <h1 class="mb-4">Edit Repository</h1>

        <form action="{{ route('update.repo', $repo->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Judul Repository</label>
                <input type="text" name="judul" value="{{ $repo->judul_repo }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea id="editor2" name="isi" class="form-control" rows="6">{{ $repo->deskripsi }}</textarea>
            </div>

            <div class="mb-3">
                <label>Status Repositori</label><br>
                <input type="radio" name="status" value="publik" {{ $repo->status == 'publik' ? 'checked' : '' }}> Publik
                <input type="radio" name="status" value="private" {{ $repo->status == 'private' ? 'checked' : '' }}>
                Private
            </div>

            <hr>
            <div id="file-container" class="mb-3"></div>
            <button type="button" class="btn btn-secondary mb-3" onclick="tambahFile()">Tambah File Baru</button>
            <br>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>

        <div class="mb-3 mt-5">
            <label>File Tambahan yang Sudah Ada : </label>
            <ul>
                @foreach ($repo->fileRepo as $file)
                    <li class="mb-2">
                        {{ $file->nama_file }}
                        <form action="{{ route('fileRepo.destroy', $file->id) }}" method="POST"
                            class="d-inline-block ms-2" id="form-delete-{{ $file->id }}">
                            @csrf
                            <button type="button" data-id="{{ $file->id }}" class="btn btn-sm btn-danger btn-delete">Hapus</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
