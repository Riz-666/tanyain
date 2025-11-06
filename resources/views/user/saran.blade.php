@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css-new/saran.css') }}">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('title', 'Tentang - Platform Berbagi Pengetahuan dan Repositori Ilmu')

@section('content')
<div class="container mt-6 body-saran" style="margin-top: -20px">

    <!-- Tentang TanyaIn -->
    <section class="section">
        <h2 class="section-title">
            <i class="fas fa-info-circle"></i>
            Tentang TanyaIn
        </h2>
        <div class="about-content">
            <p><strong>TanyaIn</strong> adalah platform berbagi pengetahuan dan repositori digital yang dirancang untuk
                mempermudah akses informasi, mempercepat proses belajar, dan mendorong kolaborasi antar pengguna.
                Melalui TanyaIn, siapa pun dapat menemukan artikel, berbagi dokumen, dan berkontribusi dalam membangun ekosistem
                pengetahuan yang terbuka dan bermanfaat bagi semua.
            </p>

            <h3><i class="fas fa-rocket"></i> Misi</h3>
            <ol>
                <li>Mendorong kolaborasi berbasis pengetahuan antar individu dan komunitas.</li>
                <li>Meningkatkan akses terhadap artikel, repositori, dan referensi ilmiah secara terbuka.</li>
                <li>Menyediakan wadah bagi pengguna untuk berbagi ide, riset, dan hasil karya digital.</li>
                <li>Mengembangkan budaya belajar bersama secara daring yang inklusif dan berkelanjutan.</li>
                <li>Mendukung pertumbuhan inovasi dan literasi digital di Indonesia.</li>
            </ol>

            <h3><i class="fas fa-eye"></i> Visi</h3>
            <p>Mewujudkan pusat pengetahuan digital yang terbuka, kolaboratif, dan terpercaya untuk mendukung kemajuan pendidikan dan inovasi di Indonesia.</p>

            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-newspaper"></i>
                    <h4>Artikel Informatif</h4>
                    <p>Kumpulan tulisan dan wawasan dari berbagai bidang pengetahuan untuk memperluas perspektif pembaca.</p>
                </div>

                <div class="feature-item">
                    <i class="fas fa-folder-open"></i>
                    <h4>Repositori Terbuka</h4>
                    <p>Akses beragam dokumen, file, dan referensi yang dapat digunakan untuk belajar dan berbagi informasi.</p>
                </div>

                <div class="feature-item">
                    <i class="fas fa-lightbulb"></i>
                    <h4>Pencarian Pintar</h4>
                    <p>Temukan artikel dan repositori relevan dengan cepat menggunakan sistem pencarian cerdas.</p>
                </div>

                <div class="feature-item">
                    <i class="fas fa-graduation-cap"></i>
                    <h4>Komunitas Belajar</h4>
                    <p>Wadah interaktif untuk saling berbagi ilmu, ide, dan pengalaman antar pengguna di seluruh Indonesia.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak & Bantuan -->
    <section class="section">
        <h2 class="section-title">
            <i class="fas fa-envelope-open-text"></i>
            Kontak dan Bantuan
        </h2>
        <div class="contact-grid">
            <div class="contact-card">
                <h4><i class="fas fa-book-open"></i> Panduan Pengguna</h4>
                <div class="contact-info">
                    <i class="fas fa-question-circle"></i>
                    <a href="{{ route('bantuan') }}">Pusat Bantuan</a>
                </div>
                <div class="contact-info">
                    <i class="fas fa-file-alt"></i>
                    <a href="{{ route('ketentuan') }}">Ketentuan Pengguna</a>
                </div>
                <div class="contact-info">
                    <i class="fas fa-shield-alt"></i>
                    <a href="{{ route('privasi') }}">Kebijakan Privasi</a>
                </div>
            </div>

            <div class="contact-card">
                <h4><i class="fas fa-life-ring"></i> Hubungi Kami</h4>
                <div class="contact-info">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:support@tanyain.id">support@tanyain.id</a>
                </div>
                <div class="contact-info">
                    <i class="fas fa-globe"></i>
                    <a href="https://tanyain.id" target="_blank">www.tanyain.id</a>
                </div>
            </div>
        </div>
    </section>

    @if (Auth::check())
    <!-- Saran -->
    <section class="section suggestion-section">
        <h2 class="section-title">
            <i class="fas fa-comment-dots"></i>
            Saran & Masukan
        </h2>

        <div class="suggestion-card" onclick="toggleForm()">
            <div class="suggestion-icon">
                <i class="fas fa-lightbulb"></i>
            </div>
            <h3 class="suggestion-title">Berikan Saran Anda</h3>
            <p class="suggestion-desc">
                Kami selalu terbuka untuk masukan, kritik, dan ide dari Anda. Sampaikan saran terbaik untuk membantu pengembangan platform ini agar semakin bermanfaat bagi semua pengguna.
            </p>
            <button class="suggestion-btn">
                <i class="fas fa-pen"></i>
                Tulis Saran
            </button>
        </div>

        <form class="suggestion-form" id="suggestionForm" method="POST" action="{{ route('saran.store') }}">
            @csrf
            <div class="form-group">
                <label for="nama"><i class="fas fa-user"></i> Nama</label>
                @auth
                    <input type="text" id="nama" name="nama" value="{{ auth()->user()->nama }}" style="background-color: rgba(225,225,225,0.314)" readonly>
                @else
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" required>
                @endauth
            </div>

            <div class="form-group">
                <label for="pesan"><i class="fas fa-comment-alt"></i> Pesan</label>
                <textarea id="pesan" name="pesan" placeholder="Tulis saran, kritik, atau masukan Anda di sini..." required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i>
                    Kirim Saran
                </button>
                <button type="button" class="cancel-btn" onclick="hideForm()">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
            </div>
        </form>
    </section>
    @endif
</div>


    <!-- Modal Structure -->
    <div id="staffModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="modal-body">
                <img id="modalFoto" src="" alt="Foto Pegawai" class="modal-foto">
                <h3 id="modalNama" class="modal-nama"></h3>
                <p id="modalJabatan" class="modal-jabatan"></p>
            </div>
        </div>
    </div>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.org-card');
            const modal = document.getElementById('staffModal');
            const modalFoto = document.getElementById('modalFoto');
            const modalNama = document.getElementById('modalNama');
            const modalJabatan = document.getElementById('modalJabatan');
            const closeBtn = document.querySelector('.close');

            // Buka modal saat card diklik
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    const nama = this.getAttribute('data-nama');
                    const jabatan = this.getAttribute('data-jabatan');
                    const foto = this.getAttribute('data-foto');

                    modalFoto.src = foto ||
                        'https://via.placeholder.com/150/cccccc/666666?text=No+Photo';
                    modalNama.textContent = nama;
                    modalJabatan.textContent = jabatan;

                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden'; // Cegah scroll background
                });
            });

            // Tutup modal saat klik tombol X
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
                document.body.style.overflow = ''; // Kembalikan scroll
            });

            // Tutup modal saat klik di luar konten
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });

            // Tutup modal dengan ESC
            window.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && modal.style.display === 'block') {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        });
    </script>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Koordinat Diskominfo Kota Bogor
            const lat = -6.5951236;
            const lng = 106.7937161;

            // Inisialisasi peta
            const map = L.map('map').setView([lat, lng], 15); // Zoom level 17

            // Tile Layer (peta dasar)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Marker lokasi
            const marker = L.marker([lat, lng]).addTo(map);

            // Popup info
            marker.bindPopup(`
      <div style="text-align: center; font-family: Arial; font-size: 14px;">
        <strong>Dinas Komunikasi dan Informatika</strong><br>
        Kota Bogor<br>
        Jl. Kapten Muslihat No.28<br>
        Bogor Tengah, Kota Bogor 16121<br>
        <a href="https://www.google.com/maps/place/Kantor+DISKOMINFO+Kota+Bogor/@-6.5951236,106.7937161,21z/data=!4m6!3m5!1s0x2e69c5b7d2e12d0d:0x9b59e38fc692d9fe!8m2!3d-6.595095!4d106.793665!16s%2Fg%2F11cnc4b1p8?entry=tts&g_ep=EgoyMDI1MDkxNy4wIPu8ASoASAFQAw%3D%3D&skid=f43bf1fe-d518-4992-9324-501cc6280cb2" target="_blank" style="color: #ff8c42; text-decoration: none; font-weight: bold;">
          Lihat di Google Maps →
        </a>
      </div>
    `).openPopup();
            // Animasi fly to location (opsional)
            setTimeout(() => {
                map.flyTo([lat, lng], 17, {
                    duration: 2,
                    easeLinearity: 0.25
                });
            }, 500);

            // Responsif: resize peta saat window diresize
            window.addEventListener('resize', function() {
                map.invalidateSize();
            });
        });
    </script>
@endsection
