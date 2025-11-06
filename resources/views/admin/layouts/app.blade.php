<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bogor.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-bogor.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-bogor.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Alpine.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.js" defer></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css">
    {{-- SweetAlert --}}
    <link rel="stylesheet" href="{{ asset('sweetalert2/dist/sweetalert2.all.min.css') }}">


    <link rel="stylesheet" href="{{ asset('admin/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/dropdown.css') }}">
    @stack('style')
</head>

<body x-data="dashboard()" :class="{ 'dark-mode': darkMode }" data-route="{{ Route::currentRouteName() }}">
<!-- backdrop -->


    <div class="dashboard-container">
        @include('admin.layouts.sidebar')


        <!-- Main Content -->
        <main class="main-content">
            @include('admin.layouts.navbar')
            <div x-show="sidebarOpen"
     x-transition.opacity
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-black bg-opacity-50 z-900 lg:hidden"></div>
            <!-- Content Area -->
            <div class="content-area">

                @yield('content')

            </div>
        </main>
    </div>

    @stack('script')
    {{-- chart JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>

    <script src="{{ asset('admin/js/dashboard.js') }}"></script>

    <script src="{{ asset('admin/js/user.js') }}"></script>
    @stack('script')



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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-restore');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id-alert-restore-');

                    Swal.fire({
                        title: 'Kembalikan Data?',
                        text: "Semua Data Akan Di kembalikan",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ff9800',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Kembalikan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-restore-alert-' + id).submit();
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


        <script>
        @if (session('auth'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('auth') }}',
            })
        @endif
    </script>
</body>

</html>
