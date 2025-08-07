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
        <h1 class="mb-4">Tambah Article</h1>
        <form action="{{ Route('update.artikel', $artikel->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-4">
                        <label for="title" class="form-label">Title Article</label>
                        <p class="text-muted small">Silahkan isi title artikel yang sesuai dengan artikel</p>
                        <input type="text" name="judul" id="" class="form-control"
                            value="{{ old('judul', $artikel->judul) }}"
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
                        <label for="content" class="form-label">Isi Article</label>
                        <p class="text-muted small">Tuliskan artikel anda</p>
                        <textarea id="editor2" class="form-control editor-area" name="isi" rows="8">{{ old('isi', $artikel->isi) }} </textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-4">
                                <label class="form-label">Upload File Article (Optional)</label>
                                <p class="text-muted small">Jika artikel yang di upload adalah yang sudah tersimpan atau
                                    dalam
                                    bentuk PDF, artikel bisa diupload disini</p>
                                <input type="file" name="file" class="form-control" id="fileInput"
                                    @error('file')
                            is-invalid
                        @enderror>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <br>
                                @if ($artikel->file)
                                    <p>File sekarang:
                                        <a class="btn btn-orange"
                                            href="{{ asset('storage/artikel-file/' . $artikel->file) }}" target="_blank">
                                            Lihat File
                                        </a>
                                    </p>
                                @else
                                    <p>Tidak Ada File Artikel</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-4">
                                <label class="form-label">Tautkan ke Repositori</label>
                                <p class="text-muted small">Artikel Bisa Ditautkan ke dalam Repository</p>
                                <select id="editArtikel" name="repositori_id" class="form-control"
                                    @error('repositori_id')
                                        is-invalid
                                @enderror>
                                    @foreach ($repositori as $repo)
                                        <option value="{{ $repo->id }}"
                                            {{ isset($artikel) && $artikel->repositori_id == $repo->id ? 'selected' : '' }}>
                                            {{ $repo->judul_repo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('repositori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
    </div>
    </div>
@endsection
