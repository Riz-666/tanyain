@extends('components.app')

@section('body')
    <div class="container mt-4">
        <div class="row">
            <!-- Profile Section -->
            <div class="col-12 mb-4">
                <div class="profile-card">
                    <div class="profile-info d-flex align-items-center">
                        <div class="profile-image">
                            @if (Auth::user()->foto == null)
                                <img src="{{ asset('storage/user-img/default-user.jpg') }}" alt=""
                                    class="rounded-circle">
                            @else
                                <img src="{{ asset('storage/user-img/' . Auth::user()->foto) }}" alt=""
                                    class="rounded-circle">
                            @endif
                        </div>
                        <div class="profile-details ms-3">
                            <h3 class="profile-name">{{ $user->nama }}</h3>
                            <p class="profile-username text-muted">{{ $user->username }}</p>
                            <p class="profile-description">{{ $user->bio }}</p>
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
                    <div class="tab-content" id="nav-tabContent">
                        <form action="{{ Route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="nama" class="mb-2">Nama</label><br>
                            <input type="text" class="form-control" name="nama" id="" value="{{ Auth::user()->nama }}" disabled>
                            <br>
                            <label for="nama" class="mb-2">Username</label><br>
                            <input type="text" class="form-control" name="username" id="" value="{{ Auth::user()->username }}" disabled>
                            <br>
                            <label for="nama" class="mb-2">email</label><br>
                            <input type="text" class="form-control" name="email" id="" value="{{ Auth::user()->email }}" disabled>
                            <br>
                            <label for="nama" class="mb-2">Bio</label><br>
                            <input type="text" class="form-control" name="bio" id="" value="{{ old('bio', Auth::user()->bio) }}">
                            <br>
                            <h2 class="mt-3 mb-3 badge bg-dark">Social Media</h2><br>
                            <label for="nama" class="mb-2">URL Instagram</label><br>
                            <input type="text" class="form-control" name="instagram" id="" value="{{ old('instagram', Auth::user()->instagram) }}">
                            <br>
                            <label for="nama" class="mb-2">URL LinkedIn</label><br>
                            <input type="text" class="form-control" name="linkedin" id="" value="{{ old('linkedin', Auth::user()->linkedin) }}">
                            <br>
                            <label for="nama" class="mb-2">URL GitHub</label><br>
                            <input type="text" class="form-control" name="github" id="" value="{{ old('github', Auth::user()->github) }}">
                            <br>
                            <label for="nama" class="mb-2">Foto</label><br>
                            <input type="file" class="form-control" name="foto" id="">
                            <br>
                            <button class="btn btn-warning btn-lg" style="margin-left:86%">Perbarui Profile</button><br>
                            Preview Foto :
                            <br>
                            @if(Auth::user()->foto == null)
                                <img src="{{ asset('storage/user-img/default-user.jpg') }}" alt="" style="width:30%; height:30%;border-radius:50%;">
                            @else
                                <img src="{{ asset('storage/user-img/' . Auth::user()->foto) }}" alt="" style="width:30%; height:30%;border-radius:50%;">
                            @endif
                        </form>
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
