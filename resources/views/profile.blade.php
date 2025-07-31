@extends('components.app')

@section('body')
<div class="container mt-4">
    <div class="row">
        <!-- Profile Section -->
        <div class="col-12 mb-4">
            <div class="profile-card">
                <div class="profile-info d-flex align-items-center">
                    <div class="profile-image">
                        <img src="{{ asset('storage/user-img/default-user.jpg') }}" alt="Profile Image" class="rounded-circle">
                    </div>
                    <div class="profile-details ms-3">
                        <h3 class="profile-name">Your Name</h3>
                        <p class="profile-username text-muted">@username</p>
                        <p class="profile-description">Deskripsi Diri</p>
                        <div class="profile-social-icons">
                            <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-icon"><i class="bi bi-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="col-12">
            <div class="content-tabs">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-article-tab" data-bs-toggle="tab" data-bs-target="#nav-article" type="button" role="tab" aria-controls="nav-article" aria-selected="true">Article</button>
                    <button class="nav-link" id="nav-repository-tab" data-bs-toggle="tab" data-bs-target="#nav-repository" type="button" role="tab" aria-controls="nav-repository" aria-selected="false">Repositori</button>
                </div>
                <div class="tab-content" id="nav-tabContent">
                    <!-- Article Tab -->
                    <div class="tab-pane fade show active" id="nav-article" role="tabpanel" aria-labelledby="nav-article-tab">
                        <div class="article-list">
                            @for ($i = 1; $i <= 6; $i++)
                            <div class="article-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="article-title">Article #{{ $i }} <span class="view-count"><i class="bi bi-eye"></i> 124</span></h5>
                                </div>
                                <p class="article-excerpt">Ini hanya permulaan kawan</p>
                                <hr>
                            </div>
                            @endfor
                        </div>
                    </div>
                    
                    <!-- Repository Tab -->
                    <div class="tab-pane fade" id="nav-repository" role="tabpanel" aria-labelledby="nav-repository-tab">
                        <div class="repository-list">
                            <!-- Repository content will go here -->
                            <p>Konten repositori akan ditampilkan di sini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile Styles */
.profile-card {
    background-color: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.profile-image img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border: 3px solid #f48223;
}

.profile-name {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 0;
}

.profile-username {
    font-size: 16px;
    margin-bottom: 5px;
}

.profile-description {
    font-size: 14px;
    margin-bottom: 10px;
}

.profile-social-icons {
    display: flex;
    gap: 15px;
}

.social-icon {
    color: #333;
    font-size: 18px;
    transition: color 0.3s;
}

.social-icon:hover {
    color: #f48223;
}

/* Tabs Styles */
.content-tabs {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.nav-tabs {
    border-bottom: none;
    padding: 0 20px;
    background-color: #f8f9fa;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    padding: 15px 20px;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #f48223;
    border-bottom: 2px solid #f48223;
    background-color: transparent;
}

.tab-content {
    padding: 20px;
}

/* Article List Styles */
.article-item {
    margin-bottom: 15px;
}

.article-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
}

.view-count {
    font-size: 14px;
    color: #6c757d;
    margin-left: 10px;
}

.article-excerpt {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 5px;
}
</style>
@endsection