@extends('components.app')

@section('body')
    <div class="container mt-4">

        <h1 class="mb-4">Tambah Article</h1>
        <form action="{{ Route('update.artikel', $artikel->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-4">
                        <label for="title" class="form-label">Title Article</label>
                        <p class="text-muted small">Silahkan isi title artikel yang sesuai dengan artikel</p>
                        <input type="text" name="judul" id="" class="form-control"
                            value="{{ old('judul', $artikel->judul) }}">
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
                                <p class="text-muted small">Jika artikel yang di upload adalah yang sudah tersimpan atau dalam
                                    bentuk PDF, artikel bisa diupload disini</p>
                                <input type="file" name="file" class="form-control" id="fileInput">
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
                                <p class="text-muted small">Artikel Bisa Dita utkan ke dalam Repository</p>
                                <select name="repositori_id" class="form-select">
                                    <option value="">-- Pilih Repositori --</option>
                                    @foreach ($repositori as $repo)
                                        <option value="{{ $repo->id }}"
                                            {{ old('repositori_id', $artikel->repositori_id ?? '') == $repo->id ? 'selected' : '' }}>
                                            {{ $repo->judul_repo }}
                                        </option>
                                    @endforeach
                                </select>
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
