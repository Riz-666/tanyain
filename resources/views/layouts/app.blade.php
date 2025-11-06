<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Manajemen Pengetahuan SPBE Kota Bogor')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bogor.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-bogor.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-bogor.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- SweetAlert --}}
    <link rel="stylesheet" href="{{ asset('sweetalert2/dist/sweetalert2.all.min.css') }}">
    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    {{-- Select2 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />
    {{-- DataTables --}}
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">



    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css-new/layouts/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css-new/layouts/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css-new/layouts/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css-new/layouts/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css-new/index.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    @yield('css')

    @yield('head')
</head>

<body>
    @if (!request()->RouteIs('admin.login'))
        @include('layouts.navbar')
    @endif

    <main>
        @yield('content')
    </main>

    @if (!request()->RouteIs('admin.login') && !request()->routeIs('login'))
        @include('layouts.footer')
    @endif

    @if (Auth::check())
        <script>
            window.CURRENT_USER_ID = {{ Auth::id() }};
        </script>
    @endif
    <!-- Scripts -->
    <script src="https://code.highcharts.com/11.2.0/modules/accessibility.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Select2 --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    {{-- Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://code.highcharts.com/11.2.0/highcharts.js"></script>
    <script src="https://code.highcharts.com/11.2.0/modules/accessibility.js"></script>


    <!-- App JavaScript -->
    <script>
        // Global app functions
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.15)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            }
        });

        // Mobile menu enhancement
        const navbarToggler = document.querySelector('.navbar-toggler');
        const navbarCollapse = document.querySelector('.navbar-collapse');

        if (navbarToggler) {
            navbarToggler.addEventListener('click', function() {
                setTimeout(() => {
                    if (navbarCollapse.classList.contains('show')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = 'auto';
                    }
                }, 300);
            });
        }

        // Close mobile menu when clicking on links
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992 && navbarCollapse) {
                    const collapse = new bootstrap.Collapse(navbarCollapse, {
                        hide: true
                    });
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
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

    @if (session('errorFile'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('errorFile') }}",
                confirmButtonColor: '#ff8c42'
            });
        </script>
    @endif


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

    <script>
        $('#selectTag').select2({
            placeholder: 'Cari / tambah tag...',
            tags: true,
            multiple: true, // bikin tetap bisa multi
            theme: 'bootstrap4',
            width: '100%'
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const link = document.getElementById('link-cari');
            if (link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const target = document.getElementById('pencarian');
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                        const input = document.getElementById('input-cari');
                        if (input) {
                            setTimeout(() => {
                                input.focus();
                                input.click(); // optional
                            }, 500);
                        }
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>

</html>
