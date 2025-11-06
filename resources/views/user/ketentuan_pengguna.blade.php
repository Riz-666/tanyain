@extends('layouts.app')
@section('title','Ketentuan Pengguna - Manajemen Pengetahuan SPBE Kota Bogor')
@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/saran.css') }}">
@endsection
@section('content')
    <div class="container mt-6 body-saran" style="margin-top: 60px">
        <!-- Tentang Website -->
        <section class="section">
            <h2 class="section-title">
                <i class="fas fa-info-circle"></i>
                Ketentuan Pengguna – MP-SPBE Kota Bogor
            </h2>
            <div class="about-content">
                <p><strong>MP-SPBE Kota Bogor</strong> Selamat datang di platform Manajemen Pengetahuan SPBE Kota Bogor,
                    sebuah sistem berbasis web yang dikembangkan untuk mendukung implementasi Sistem Pemerintahan Berbasis Elektronik (SPBE).
                    Platform ini memfasilitasi berbagi pengetahuan melalui artikel, dokumen, dan repositori digital.
                    MP-SPBE Kota Bogor memungkinkan pengguna untuk mencari, membaca, dan berbagi informasi secara efisien.
                    Ketentuan ini merupakan perjanjian yang wajib dibaca, dipahami, dan disetujui oleh pengunjung
                    (selanjutnya disebut “Anda” atau “Pengguna”) sebelum mengakses dan/atau menggunakan MP-SPBE Kota Bogor.
                    Dengan mengakses dan menggunakan layanan MP-SPBE Kota Bogor, Anda dianggap telah membaca, memahami, dan menyetujui seluruh isi ketentuan ini.
                    Jika Anda tidak setuju dengan sebagian atau seluruh ketentuan ini, mohon untuk tidak menggunakan MP-SPBE Kota Bogor.
                    Harap baca ketentuan ini dengan cermat, karena ketidakpatuhan dapat berakibat pada penangguhan akun, penghapusan konten,
                    hingga tanggung jawab perdata maupun pidana sesuai hukum yang berlaku.</p>

                <h3><i class="fas fa-book"></i> Penggunaan Umum</h3>
                <p>Kecuali diizinkan secara tertulis oleh pengelola MP-SPBE Kota Bogor, kami memberikan kepada Anda hak
                    non-eksklusif, terbatas, dapat dibatalkan, tidak dapat dialihkan, dan tidak dapat disublisensikan untuk
                    menggunakan MP-SPBE Kota Bogor hanya untuk tujuan internal, pembelajaran, dan non-komersial.</p>

                <p>Anda setuju untuk tidak melakukan hal-hal berikut:</p>
                <ol>
                    <li>Menggunakan MP-SPBE Kota Bogor untuk tujuan komersial tanpa izin resmi.</li>
                    <li>Menghapus, merusak, menonaktifkan, atau mengganggu fitur keamanan dalam platform MP-SPBE Kota Bogor.</li>
                    <li>Mengunggah, mengakses, atau membagikan data yang bukan milik Anda atau tanpa izin sah dari pemiliknya.</li>
                    <li>Membuat klaim palsu, menyesatkan, atau menggambarkan secara keliru konten maupun layanan MP-SPBE Kota Bogor.</li>
                    <li>Menggunakan MP-SPBE Kota Bogor untuk tujuan ilegal, melanggar hukum, atau yang bertentangan dengan norma kesusilaan.</li>
                    <li>Mengeksploitasi atau menyebarluaskan celah keamanan (vulnerability) tanpa pelaporan resmi.</li>
                    <li>Menggunakan bot, crawler, atau perangkat otomatis lain untuk mengambil sebagian/seluruh data tanpa izin.</li>
                    <li>Mengubah tampilan, antarmuka, atau mengalihkan tautan MP-SPBE Kota Bogor tanpa persetujuan resmi.</li>
                    <li>Mengunggah konten yang melanggar hak cipta, mengandung SARA, pornografi, ujaran kebencian, atau merugikan pihak lain.</li>
                </ol>

                <h3><i class="fas fa-balance-scale"></i> Pembatasan Tanggung Jawab dan Jaminan</h3>
                <p>MP-SPBE Kota Bogor tidak bertanggung jawab atas kerugian langsung maupun tidak langsung, termasuk kehilangan data
                    atau kerugian lainnya yang timbul dari:</p>
                <ul>
                    <li>Akses tidak sah, peretasan, atau pelanggaran keamanan.</li>
                    <li>Penggunaan informasi yang diperoleh dari pihak ketiga yang terhubung dengan MP-SPBE Kota Bogor.</li>
                    <li>Gangguan teknis, keterlambatan, atau kesalahan layanan yang berada di luar kendali kami.</li>
                </ul>
                <p>Kami tidak menjamin bahwa MP-SPBE Kota Bogor akan selalu tersedia, bebas dari gangguan, atau sepenuhnya aman dari ancaman digital.</p>

                <h3><i class="fas fa-copyright"></i> Hak Kekayaan Intelektual</h3>
                <p>Seluruh perangkat lunak, desain, logo, dan elemen dalam MP-SPBE Kota Bogor merupakan milik tim pengelola
                    dan dilindungi oleh hukum. Hak cipta artikel, repositori, atau file yang diunggah tetap menjadi milik penulis/pengunggah.
                    Namun, dengan mengunggah konten ke MP-SPBE Kota Bogor, Anda memberikan izin non-eksklusif
                    kepada pengelola untuk menampilkan, mendistribusikan, dan menyimpan konten tersebut dalam platform.</p>
                <p>Pengguna tidak diperbolehkan menyalin, mereproduksi, atau menggunakan elemen kekayaan intelektual MP-SPBE Kota Bogor
                   tanpa izin tertulis dari kami.</p>

                <h3><i class="fas fa-cogs"></i> Perubahan Layanan</h3>
                <p>Kami berhak mengubah, menambahkan, atau menghentikan sebagian maupun seluruh layanan MP-SPBE Kota Bogor sewaktu-waktu dengan atau tanpa pemberitahuan terlebih dahulu.</p>

                <h3><i class="fas fa-gavel"></i> Hukum yang Berlaku & Penyelesaian Sengketa</h3>
                <p>Ketentuan ini diatur dan ditafsirkan sesuai dengan hukum Republik Indonesia.</p>
                <ul>
                    <li>Segala sengketa atau perselisihan akan diselesaikan terlebih dahulu melalui musyawarah dan mufakat.</li>
                    <li>Jika dalam 30 (tiga puluh) hari sengketa tidak terselesaikan, maka penyelesaian akan dilakukan melalui Pengadilan Negeri di wilayah hukum tempat pengelola MP-SPBE Kota Bogor berada.</li>
                </ul>
            </div>
        </section>
        <script>
            function toggleForm() {
                const form = document.getElementById('suggestionForm');
                const card = document.querySelector('.suggestion-card');

                if (form.classList.contains('active')) {
                    hideForm();
                } else {
                    showForm();
                }
            }

            function showForm() {
                const form = document.getElementById('suggestionForm');
                const card = document.querySelector('.suggestion-card');
                const btn = document.querySelector('.suggestion-btn');

                form.classList.add('active');
                card.style.opacity = '0.7';
                btn.innerHTML = '<i class="fas fa-chevron-up"></i> Tutup Form';

                setTimeout(() => {
                    form.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 100);
            }

            function hideForm() {
                const form = document.getElementById('suggestionForm');
                const card = document.querySelector('.suggestion-card');
                const btn = document.querySelector('.suggestion-btn');

                form.classList.remove('active');
                card.style.opacity = '1';
                btn.innerHTML = '<i class="fas fa-pen"></i> Tulis Saran';
                form.reset();
            }
        </script>
    @endsection
