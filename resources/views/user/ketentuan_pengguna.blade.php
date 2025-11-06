@extends('layouts.app')
@section('title','Ketentuan Pengguna - TanyaIn | Platform Berbagi Pengetahuan & Repositori Ilmu Indonesia')
@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/saran.css') }}">
@endsection
@section('content')
    <div class="container mt-6 body-saran" style="margin-top: 60px">
        <!-- Ketentuan Pengguna -->
        <section class="section">
            <h2 class="section-title">
                <i class="fas fa-info-circle"></i>
                Ketentuan Pengguna – TanyaIn
            </h2>
            <div class="about-content">
                <p><strong>TanyaIn</strong> adalah platform berbasis web yang dikembangkan untuk memfasilitasi berbagi
                    pengetahuan, artikel, dan repositori ilmu antar pengguna di Indonesia. Melalui TanyaIn, pengguna dapat
                    mencari, membaca, dan membagikan informasi secara efisien dan terstruktur untuk mendukung budaya transfer
                    knowledge.</p>

                <p>Ketentuan ini merupakan perjanjian antara Anda (“Pengguna”) dan pengelola TanyaIn yang berisi aturan dalam
                    menggunakan seluruh layanan di dalam platform. Dengan mengakses atau menggunakan TanyaIn, Anda dianggap
                    telah membaca, memahami, dan menyetujui seluruh ketentuan ini. Jika Anda tidak setuju, mohon untuk tidak
                    melanjutkan penggunaan layanan TanyaIn.</p>

                <h3><i class="fas fa-book"></i> Penggunaan Umum</h3>
                <p>TanyaIn memberikan hak terbatas, non-eksklusif, tidak dapat dipindahtangankan, dan dapat dicabut kepada
                    Pengguna untuk menggunakan platform ini hanya untuk tujuan pembelajaran, penelitian, dan berbagi
                    pengetahuan secara non-komersial.</p>

                <p>Anda setuju untuk tidak melakukan hal-hal berikut:</p>
                <ol>
                    <li>Menggunakan TanyaIn untuk tujuan komersial tanpa izin resmi dari pengelola.</li>
                    <li>Menghapus, menonaktifkan, atau mengganggu fitur keamanan dalam platform.</li>
                    <li>Mengunggah konten yang bukan milik sendiri tanpa izin sah dari pemiliknya.</li>
                    <li>Menyebarkan informasi yang menyesatkan, palsu, atau berpotensi merugikan pihak lain.</li>
                    <li>Menggunakan TanyaIn untuk tujuan ilegal, spam, atau melanggar norma sosial.</li>
                    <li>Mengeksploitasi celah keamanan tanpa pelaporan resmi.</li>
                    <li>Menggunakan bot, crawler, atau alat otomatis untuk mengambil data tanpa izin.</li>
                    <li>Mengubah tampilan, antarmuka, atau mengalihkan tautan platform tanpa izin tertulis.</li>
                    <li>Mengunggah konten yang melanggar hak cipta, mengandung SARA, pornografi, atau ujaran kebencian.</li>
                </ol>

                <h3><i class="fas fa-balance-scale"></i> Pembatasan Tanggung Jawab dan Jaminan</h3>
                <p>Pengelola TanyaIn tidak bertanggung jawab atas kerugian langsung maupun tidak langsung, termasuk kehilangan data,
                    akibat dari:</p>
                <ul>
                    <li>Akses tidak sah, peretasan, atau pelanggaran keamanan.</li>
                    <li>Penggunaan konten yang bersumber dari pihak ketiga.</li>
                    <li>Gangguan teknis, keterlambatan, atau kegagalan sistem di luar kendali pengelola.</li>
                </ul>
                <p>Kami tidak menjamin TanyaIn akan selalu tersedia tanpa gangguan atau sepenuhnya bebas dari ancaman digital,
                    namun kami berkomitmen untuk terus meningkatkan keamanan dan performanya.</p>

                <h3><i class="fas fa-copyright"></i> Hak Kekayaan Intelektual</h3>
                <p>Seluruh logo, desain, tampilan, dan elemen sistem dalam TanyaIn adalah milik pengembang dan dilindungi oleh
                    hukum hak cipta. Hak cipta artikel atau file yang diunggah tetap menjadi milik penulis atau pengunggah.
                    Dengan mengunggah konten ke TanyaIn, Anda memberikan izin non-eksklusif kepada pengelola untuk menampilkan,
                    menyimpan, dan mendistribusikan konten tersebut di dalam platform.</p>
                <p>Dilarang menyalin, memodifikasi, atau menggunakan elemen TanyaIn untuk kepentingan lain tanpa izin tertulis
                    dari pengelola.</p>

                <h3><i class="fas fa-cogs"></i> Perubahan Layanan</h3>
                <p>Pengelola TanyaIn berhak melakukan perubahan, pembaruan, atau penghentian sebagian maupun seluruh fitur
                    layanan kapan pun dengan atau tanpa pemberitahuan terlebih dahulu.</p>

                <h3><i class="fas fa-gavel"></i> Hukum yang Berlaku & Penyelesaian Sengketa</h3>
                <p>Ketentuan ini diatur dan ditafsirkan sesuai dengan hukum Republik Indonesia.</p>
                <ul>
                    <li>Setiap sengketa atau perselisihan akan diselesaikan terlebih dahulu secara musyawarah.</li>
                    <li>Apabila dalam 30 (tiga puluh) hari tidak tercapai kesepakatan, penyelesaian dilakukan melalui
                        Pengadilan Negeri di wilayah domisili pengelola TanyaIn.</li>
                </ul>

                <h3><i class="fas fa-thumbs-up"></i> Persetujuan</h3>
                <p>Dengan menggunakan platform TanyaIn, Anda dianggap telah membaca, memahami, dan menyetujui seluruh isi
                    ketentuan ini serta bersedia mematuhi segala peraturan yang berlaku di dalamnya.</p>
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
