@extends('components.app')

@section('body')
    <div class="container mt-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h1 class="mb-4">Tambah Repository</h1>
        <form action="{{ Route('add.repo') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-4">
                        <label for="title" class="form-label">Title Repository</label>
                        <p class="text-muted small">Silahkan isi title artikel yang sesuai dengan artikel</p>
                        <input type="text" name="judul" id="" class="form-control"
                            @error('judul')
                                is-invalid
                        @enderror>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-4">
                        <label for="content" class="form-label">Deskripsi Repository</label>
                        <p class="text-muted small">Tuliskan Deskripsi Repositori anda</p>
                        <textarea id="editor2" class="form-control editor-area" name="isi" rows="8"></textarea>
                    </div>

                    <div class="row" id="file-container"></div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-secondary" onclick="tambahFile()">Tambah File</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-4">
                                <label>Status Repositori:</label><br>
                                <input type="radio" name="status" value="publik" id="publik"
                                    {{ old('status') == 'publik' ? 'checked' : '' }}
                                    @error('status')
                                is-invalid
                        @enderror>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <label for="publik">Publik</label><br>
                                <input type="radio" name="status" value="private" id="private"
                                    {{ old('status') == 'private' ? 'checked' : '' }}
                                    @error('status')
                                is-invalid
                        @enderror>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <label for="private">Private</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
