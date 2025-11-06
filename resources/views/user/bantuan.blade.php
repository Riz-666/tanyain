@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/saran.css') }}">
@endsection
@section('title', 'Bantuan Pengguna - Manajemen Pengetahuan SPBE Kota Bogor')
@section('content')
    <div class="container mt-6 body-saran" style="margin-top: 60px">
        <!-- Tentang Website -->
        <section class="section">
            <h2 class="section-title">
                <i class="fas fa-info-circle"></i>
                Bantuan Pengguna – MP-SPBE Kota Bogor
            </h2>
            <div class="about-content">
                <h3><i class="fas fa-globe"></i> Apa itu MP-SPBE Kota Bogor?</h3>
                <p><strong>MP-SPBE Kota Bogor</strong> adalah inisiatif manajemen pengetahuan berbasis digital yang
                    mendukung pelaksanaan Sistem Pemerintahan Berbasis Elektronik (SPBE).
                    Platform ini menyediakan artikel, dokumen, dan repositori sebagai media berbagi pengetahuan, informasi,
                    serta pengalaman yang dapat diakses secara internal, cepat, dan terkelola dengan baik.</p>

                <h3><i class="fas fa-newspaper"></i> Artikel</h3>
                <p>Artikel berisi pengetahuan, informasi, maupun hasil analisis yang dibagikan untuk mendukung penerapan
                    SPBE.
                    Kontennya dapat berupa panduan, laporan, maupun penjelasan singkat terkait kebijakan dan praktik terbaik
                    SPBE.</p>

                <h3><i class="fas fa-folder-open"></i> Repositori</h3>
                <p>Repositori berfungsi sebagai tempat penyimpanan file atau dokumen pendukung pelaksanaan SPBE.
                    File dapat berupa dokumen, presentasi, dataset, maupun materi referensi yang bisa diakses dan
                    dimanfaatkan perangkat daerah.</p>

                <h3><i class="fa-solid fa-circle-exclamation"></i> Akses Konten</h3>
                <ol>
                    <li>Tanpa Login: Pengunjung internal dapat mencari dan membaca artikel maupun mengunduh repositori.</li>
                    <li>Dengan Login (Admin/Contributor): Pengguna tertentu dapat menambahkan artikel maupun repositori
                        sesuai hak akses yang diberikan.</li>
                </ol>

                <h3><i class="fas fa-bullseye"></i> Tujuan</h3>
                <ol>
                    <li>Mendukung pelaksanaan MP-SPBE Kota Bogor melalui akses pengetahuan yang terstruktur.</li>
                    <li>Mempermudah perangkat daerah mencari, membaca, dan mengunduh konten yang relevan.</li>
                    <li>Menjadi pusat manajemen pengetahuan SPBE yang dikelola secara akuntabel.</li>
                </ol>

                <h3><i class="fas fa-shield"></i> Perubahan Kebijakan</h3>
                <p>MP-SPBE Kota Bogor berhak mengubah atau memperbarui kebijakan penggunaan sewaktu-waktu. Jika ada
                    perubahan signifikan, pengguna akan diinformasikan.
                    Dengan terus menggunakan platform ini, pengguna dianggap menyetujui perubahan tersebut.</p>

                <h3><i class="fas fa-ban"></i> Perangkat Lunak & Ekstensi yang Dilarang</h3>
                <p>Untuk menjaga keamanan data, pengguna dilarang menggunakan perangkat lunak pihak ketiga seperti bot,
                    crawler, plug-in, atau ekstensi browser yang dapat mengambil data, mengubah tampilan, atau
                    mengotomatisasi aktivitas. Larangan ini mencakup:</p>
                <ul>
                    <li>Mengambil sebagian atau seluruh data tanpa izin.</li>
                    <li>Mengubah tampilan atau antarmuka platform tanpa persetujuan resmi.</li>
                    <li>Melakukan deep-link ke konten/layanan tanpa persetujuan resmi.</li>
                </ul>

                <h3><i class="fas fa-scroll"></i> Persetujuan</h3>
                <p>Dengan menggunakan MP-SPBE Kota Bogor, pengguna dianggap telah membaca, memahami, dan menyetujui seluruh
                    kebijakan penggunaan ini.</p>
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
