<header class="hero-section">
      <nav class="navbar">
        <div class="logo">
          <h1 class="text-wrapper-3">Logo</h1>
        </div>
        <ul class="nav-item">
<<<<<<< Updated upstream
          <li><a href="#" class="button-repositori">Beranda</a></li>
          <li><a href="#" class="nav-link">Repositori</a></li>
          <li><a href="#" class="nav-link">Article</a></li>
          <li><a href="{{ route('login') }}" class="nav-link">Login</a></li>
          <li><a href="#" class="nav-link">Login</a></li>
=======
            <li><a href="/" class="button-repositori">Beranda</a></li>
            <li><a href="{{ route('repository') }}" class="nav-link">Repositori</a></li>
            <li><a href="{{ route('article') }}" class="nav-link">Article</a></li>

            @if (Auth::user() && Auth::user()->role === 'user')
                <li class="nav-link dropdown">
                    <a class="nav-link d-flex align-items-end" id="UserDropdown" href="#"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        @if (Auth::user()->foto)
                            <img class="avatar" src="{{ asset('storage/user-img/' . Auth::user()->foto) }}">
                        @else
                            <img class="avatar" src="{{ asset('storage/user-img/default-user.jpg') }}">
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li class="dropdown-item text-center">
                            @if (Auth::user()->foto)
                                <img class="avatar dropdown-avatar mb-2"
                                    src="{{ asset('storage/user-img/' . Auth::user()->foto) }}">
                            @else
                                <img class="avatar dropdown-avatar mb-2"
                                    src="{{ asset('storage/user-img/default-user.jpg') }}">
                            @endif
                            <p class="email mb-0">{{ Auth::user()->email }}</p>
                        </li>
                        <li>
                            <hr>
                        </li>
                        <li><a class="dropdown-item edit-title" href="{{ route('profile') }}"><i class="fa fa-user"></i> Profile</a></li>
                        <li><a class="dropdown-item edit-title" href="#"><i class="fa fa-pen-to-square"></i> Edit
                                Profile</a></li>
                        <form action="{{ Route('logout') }}" method="post">
                            @csrf
                        <li><button class="dropdown-item logout-title"><i class="fa fa-power-off"></i>
                                Logout</button></li>
                        </form>
                    </ul>
                </li>
            @else
                <li><a href="{{ route('login') }}" class="nav-link">Login</a></li>
            @endif
>>>>>>> Stashed changes
        </ul>
      </nav>
      <form class="search" role="search">
        <div class="search-field">
          <label for="search-input" class="visually-hidden">Search</label>
          <input type="search" id="search-input" class="query-wrapper" placeholder="Search" />
        </div>
        <button type="submit" class="search-field-wrapper" aria-label="Submit search">
          <div class="icon-magnifyingglass-wrapper">
            <img class="icon-magnifyingglass" src="img/magnifyingglass.svg" alt="" />
          </div>
        </button>
      </form>
    </header>
