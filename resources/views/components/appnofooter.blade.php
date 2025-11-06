<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    {{-- Bootstrap 5 (hanya satu versi) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- SweetAlert --}}
    <link rel="stylesheet" href="{{ asset('sweetalert2/dist/sweetalert2.all.min.css') }}">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    {{-- Select2 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />


    {{-- DataTables --}}
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/repository.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleguide.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/article.css') }}">
    <link rel="stylesheet" href="{{ asset('css/create_article.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detail_article.css') }}">
    <link rel="stylesheet" href="{{ asset('css/saran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alert.css') }}">
</head>

<body>
    <div id="preloader">
        <div class="spinner"></div>
    </div>

    @include('components.navbar')

    <main class="main-content">
        @yield('body')
    </main>

    {{-- Js-Custome --}}
    <script src="{{ asset('admin/js/style.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JS Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- FontAwesome --}}
    <script src="{{ asset('fontawesome/js/all.min.js') }}"></script>

    {{-- Select2 --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

    {{-- Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="{{ asset('js-custome/style.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function imageHandler() {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.click();

                input.onchange = async () => {
                    const file = input.files[0];
                    if (file) {
                        const formData = new FormData();
                        formData.append('image', file);

                        try {
                            const res = await fetch('{{ route('upload.image') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content'),
                                },
                            });

                            if (!res.ok) throw new Error('Network response not OK');

                            const data = await res.json();

                            if (data.success) {
                                const range = quill.getSelection(true);
                                quill.insertEmbed(range.index, 'image', data.url);
                                quill.setSelection(range.index + 1);
                            } else {
                                alert('Upload gambar gagal');
                            }
                        } catch (error) {
                            alert('Error saat upload gambar. Cek console.');
                            console.error('Upload image error:', error);
                        }
                    }
                };
            }

            const quill = new Quill('#editor-quill', {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: [
                            ['bold', 'italic', 'underline', 'strike'],
                            ['link', 'image'],
                            [{
                                list: 'ordered'
                            }, {
                                list: 'bullet'
                            }],
                            [{
                                header: [1, 2, 3, false]
                            }],
                            ['clean'],
                        ],
                        handlers: {
                            image: imageHandler,
                        },
                    },
                },
            });

            // Load isi artikel lama (old input)
            // Baru setelah quill siap, set isi artikel
            const isiArtikel = {!! json_encode(old('isi', $artikel->isi ?? '')) !!};
            quill.root.innerHTML = isiArtikel;

            // Saat submit, isi Quill dimasukkan ke textarea agar terkirim ke server
            const form = document.getElementById('form-artikel');
            form.onsubmit = function(e) {
                document.getElementById('isi').value = quill.root.innerHTML;
            };
        });
    </script>


    <script>
        function toggleNavbar() {
            document.getElementById('navLinks').classList.toggle('show');
        }
    </script>

    <script>
        function tambahFile() {
            const container = document.getElementById('file-container');
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'file_tambahan[]';
            input.className = 'form-control mb-2';
            container.appendChild(input);
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#table1').DataTable({
                responsive: true,
                columnDefs: [{
                    defaultContent: "-",
                    targets: "_all"
                }],
            });
        });
    </script>


    <script>
        @if (session('auth'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('auth') }}',
            })
        @endif
    </script>

    <script>
        @if (session('saranError'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('saranError') }}',
            })
        @endif
    </script>

    <script>
        @if (session('duplicate'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('duplicate') }}',
            })
        @endif
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Masukan Ke Dalam Trash?',
                        text: "Data Yang Berada Dalam Trash Akan di Hapus Dalam 20 Hari",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-delete-' + id).submit();
                        }
                    });
                });
            });
        });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete-permanent');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id-alert-');

                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Data yang di hapus tidak bisa di kembalikan",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-delete-alert-' + id).submit();
                        }
                    });
                });
            });
        });
    </script>

    @if (session('login'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "{{ session('login') }}",
                    showConfirmButton: false,
                    timer: 1100,
                    width: '250px',
                    customClass: {
                        title: 'swal-title-small', // untuk font title
                        icon: 'swal-icon-small' // untuk icon
                    }
                });
            });
        </script>
    @endif

    @if (session('logout'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "{{ session('logout') }}",
                    showConfirmButton: false,
                    timer: 1100,
                    width: '100px',
                    customClass: {
                        title: 'swal-title-small', // untuk font title
                        icon: 'swal-icon-small' // untuk icon
                    }
                });
            });
        </script>
    @endif


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.body.classList.add('fade-in');
            window.addEventListener('load', function() {
                document.body.classList.add('loaded');

                const preloader = document.getElementById('preloader');
                if (preloader) {
                    preloader.style.opacity = '0';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 500);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#selectRepo').val('').select2({
                placeholder: 'Cari Repository',
                theme: 'bootstrap4'
            });
        });
    </script>

    @isset($artikel)
        @if (is_object($artikel) && isset($artikel->repositori_id))
            <script>
                $('#editArtikel').val('{{ $artikel->repositori_id }}').trigger('change').select2({
                    theme: 'bootstrap4'
                });
            </script>
        @endif
    @endisset

</body>

</html>
