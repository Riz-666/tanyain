<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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

    {{-- CkEditor --}}
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css">

    {{-- Select2 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('select2/dist/select2-bootstrap4.min.css') }}">

    {{-- DataTables --}}
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleguide.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/article.css') }}">
    <link rel="stylesheet" href="{{ asset('css/create_article.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detail_article.css') }}">
</head>

<body>

    @include('components.navbar')

    <main class="main-content">
        @yield('body')
    </main>

    @include('components.footer')

    {{-- JS Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLR0b6EGonoxxX6K8ZJqRA5E6lYX9boCRX6iO9diDj" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- FontAwesome --}}
    <script src="{{ asset('fontawesome/js/all.min.js') }}"></script>

    {{-- Select2 --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    {{-- Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    {{-- CKEditor (latest 4.x) --}}
    <script src="https://cdn.ckeditor.com/4.4.1/standard/ckeditor.js"></script>

    {{-- Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        CKEDITOR.replace('editor2', {
            allowedContent: true,
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
    @if(session('auth'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Dilarang Akses Halaman Ini !!!',
        })
    @endif
</script>
</body>

</html>
