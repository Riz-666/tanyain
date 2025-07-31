@extends('components.app')

@section('body')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Article Title and Info -->
            <div class="article-detail-header">
                <h2 class="article-title">Cara Handle Laravel Error 500</h2>
                <div class="article-info">
                    <span class="article-date">Dibuat Jul 20, 2025</span>
                    <span class="article-views">Dilihat 124 times</span>
                </div>
                <div class="article-author-info">
                    <div class="author-avatar-container">
                        <img src="{{ asset('storage/user-img/default-user.jpg') }}" class="author-avatar" alt="Profile">
                    </div>
                    <div class="author-detail">
                        <p class="author-name">Mas Agung</p>
                        <p class="author-stats">15.3k</p>
                    </div>
                </div>
            </div>

            <!-- Language Selector -->
            <div class="language-selector">
                <a href="#" class="btn btn-sm btn-orange active">English</a>
                <a href="#" class="btn btn-sm btn-outline-orange">Bahasa</a>
            </div>

            <!-- Article Content -->
            <div class="article-content mt-4">
                <p>Jadi untuk melakukan pengindiaan Error 500 di laravel kita harus mengetahui apa dulu error nya dan kita akan memecahkan bersama sama</p>
                
                <!-- PDF Viewer -->

                        <embed src=/storage/pdfan/jurnal1.pdf height=1000 width=100%>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection