 <!-- Footer -->
 <footer class="footer">
     <div class="footer-container">
         <div class="footer-grid">
             <!-- Informasi Sistem -->
             <div class="footer-section">
                 <h4 class="footer-title">
                     <i class="fas fa-server"></i>
                     Statistik Sistem
                 </h4>
                 <div class="footer-content">
                     <div class="system-info">
                         <i class="fas fa-newspaper"></i>
                         <span> {{ $totalArtikel }} Articles</span>
                     </div>
                     <div class="system-info">
                         <i class="fas fa-folder-open"></i>
                         <span>{{ $totalRepo }} Repositories</span>
                     </div>
                     <div class="system-info">
                         <i class="fas fa-tag"></i>
                         <span>{{ $totalTag }} Tags</span>
                     </div>
                     <div class="system-info">
                         <i class="fas fa-users"></i>
                         <span>{{ $totalPengguna }} Pengguna</span>
                     </div>
                 </div>
             </div>

             <!-- Navigasi Internal -->
             <div class="footer-section">
                 <h4 class="footer-title">
                     <i class="fas fa-sitemap"></i>
                     Navigasi Cepat
                 </h4>
                 <div class="footer-content">
                     <a href="/" class="footer-link {{ request()->is('/') ? 'active' : '' }}">
                         <i class="fas fa-home"></i>
                         Dashboard
                     </a>
                     <a href="{{ route('article') }}" class="footer-link {{ request()->is('article') ? 'active' : '' }}">
                         <i class="fas fa-newspaper"></i>
                         Artikel
                     </a>
                     <a href="{{ route('repository') }}" class="footer-link {{ request()->is('repository') ? 'active' : '' }}">
                         <i class="fas fa-folder-open"></i>
                         Repository
                     </a>
                     <a href="{{ route('saran') }}" class="footer-link {{ request()->is('saran') ? 'active' : '' }}">
                         <i class="fas fa-info-circle"></i>
                         Tentang
                     </a>
                 </div>
             </div>

             <!-- Kontak Internal -->
             <div class="footer-section">
                 <h4 class="footer-title">
                     <i class="fas fa-headset"></i>
                     Kontak
                 </h4>
                 <div class="footer-content">
                     <div class="contact-item">
                         <i class="fas fa-envelope"></i>
                         <a href="#">admin@tanyain.internal</a>
                     </div>
                     <div class="contact-item">
                         <i class="fas fa-phone"></i>
                         <a href="#">0838 - xxxx - xxxx</a>
                     </div>
                     <div class="contact-item">
                         <i class="fab fa-whatsapp"></i>
                         <a href="#">Grup WhatsApp Internal</a>
                     </div>
                 </div>
             </div>


         </div>

         <!-- Footer Bottom -->

         <div class="footer-bottom">
             <div class="footer-bottom-content">
                 <div class="copyright">
                     <p>
                         <i class="fas fa-copyright"></i>
                         2025 TanyaIn. All Rights Reserved.
                     </p>
                 </div>
             </div>
         </div>
     </div>
 </footer>

