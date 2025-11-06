@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/saran.css') }}">
@endsection

@section('title', 'Bantuan Pengguna - Platform Berbagi Pengetahuan & Repositori Digital')
@section('content')
<div class="container mt-6 body-saran" style="margin-top: 60px">
    <!-- Bantuan Pengguna -->
    <section class="section">
        <h2 class="section-title">
            <i class="fas fa-info-circle"></i>
            Bantuan Pengguna – TanyaIn
        </h2>

        <div class="about-content">
            <h3><i class="fas fa-globe"></i> Apa itu Platform Berbagi Pengetahuan?</h3>
            <p>
                <strong>Platform Berbagi Pengetahuan</strong> adalah wadah digital yang menyediakan artikel, dokumen, dan repositori terbuka
                sebagai sarana berbagi informasi, ide, serta pengalaman. Tujuannya adalah untuk memudahkan pengguna dalam menemukan referensi,
                memperluas wawasan, dan berkontribusi dalam pengembangan ilmu pengetahuan secara kolaboratif.
            </p>

            <h3><i class="fas fa-newspaper"></i> Artikel</h3>
            <p>
                Artikel berisi tulisan informatif, panduan, atau hasil riset yang dibagikan oleh pengguna. Konten ini dapat dimanfaatkan untuk
                belajar, menambah wawasan, dan mendukung pengembangan ide di berbagai bidang.
            </p>

            <h3><i class="fas fa-folder-open"></i> Repositori</h3>
            <p>
                Repositori berfungsi sebagai tempat penyimpanan file dan dokumen digital. Pengguna dapat mengunduh maupun berbagi berkas
                seperti dokumen, presentasi, atau materi pembelajaran untuk mendukung kegiatan berbasis pengetahuan.
            </p>

            <h3><i class="fa-solid fa-circle-exclamation"></i> Akses Konten</h3>
            <ol>
                <li><strong>Tanpa Login:</strong> Pengunjung dapat mencari dan membaca artikel serta mengunduh file repositori publik.</li>
                <li><strong>Dengan Login:</strong> Pengguna terdaftar dapat menulis artikel, menambah repositori, dan berkontribusi dalam pengelolaan konten.</li>
            </ol>

            <h3><i class="fas fa-bullseye"></i> Tujuan Platform</h3>
            <ol>
                <li>Mempermudah pengguna mengakses dan berbagi pengetahuan secara terbuka.</li>
                <li>Mendukung kolaborasi dalam pengembangan ide dan riset digital.</li>
                <li>Membangun ekosistem pembelajaran yang inklusif dan berkelanjutan.</li>
            </ol>

            <h3><i class="fas fa-shield"></i> Kebijakan & Keamanan</h3>
            <p>
                Untuk menjaga kenyamanan dan keamanan seluruh pengguna, sistem menerapkan kebijakan perlindungan data serta pembatasan akses terhadap aktivitas otomatis.
                Dilarang menggunakan perangkat lunak seperti bot, crawler, atau plug-in yang dapat mengubah tampilan, mengambil data, atau mengotomatisasi aktivitas di platform ini.
            </p>
            <ul>
                <li>Mengambil sebagian atau seluruh data tanpa izin.</li>
                <li>Mengubah tampilan atau antarmuka platform tanpa persetujuan resmi.</li>
                <li>Melakukan deep-link ke konten tanpa izin.</li>
            </ul>

            <h3><i class="fas fa-scroll"></i> Persetujuan</h3>
            <p>
                Dengan menggunakan platform ini, pengguna dianggap telah membaca, memahami, dan menyetujui seluruh ketentuan serta kebijakan yang berlaku.
                Platform dapat memperbarui isi dan kebijakan sewaktu-waktu untuk meningkatkan keamanan dan kenyamanan bersama.
            </p>
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
                form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
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
</div>
@endsection
