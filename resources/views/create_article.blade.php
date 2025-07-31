@extends('components.app')

@section('body')
<div class="container mt-4">

    <h2 class="mb-4">Tambah Article</h2>
    <form action="#" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="mb-4">
                    <label for="title" class="form-label">Title Article</label>
                    <p class="text-muted small">Silahkan isi title artikel yang sesuai dengan artikel</p>
                    <div class="editor-toolbar">
                        <button type="button" class="btn btn-sm"><i class="bi bi-type-bold"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-type-italic"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-type-underline"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-list-ol"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-list-ul"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-link"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-image"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-code"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-quote"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-justify"></i></button>
                    </div>
                    <textarea id="title" class="form-control editor-area" rows="5"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-4">
                    <label for="content" class="form-label">Isi Article</label>
                    <p class="text-muted small">Tuliskan artikel anda</p>
                    <div class="editor-toolbar">
                        <button type="button" class="btn btn-sm"><i class="bi bi-type-bold"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-type-italic"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-type-underline"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-list-ol"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-list-ul"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-link"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-image"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-code"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-quote"></i></button>
                        <button type="button" class="btn btn-sm"><i class="bi bi-justify"></i></button>
                    </div>
                    <textarea id="content" class="form-control editor-area" rows="8"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-4">
                            <label class="form-label">Upload File Article (Optional)</label>
                            <p class="text-muted small">Jika artikel yang upload adalah yang pernah tersimpan, artikel akan diupload disini</p>
                            <input type="file" class="form-control" id="fileInput" multiple>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
</div>
@endsection