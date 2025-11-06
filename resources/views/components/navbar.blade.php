<nav class="navbar fixed-top">
    <div class="container">
        <div class="logo">TanyaIn</div>

        <button class="hamburger" onclick="toggleNavbar()">☰</button>

        <ul class="nav-links" id="navLinks">
            <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }} awal">Beranda</a></li>
            <li><a href="{{ route('repository') }}"
                    class="{{ request()->is('repository') ? 'active' : '' }} awal">Repositori</a></li>
            <li><a href="{{ route('article') }}" class="{{ request()->is('article') ? 'active' : '' }} awal">Artikel</a>
            </li>
            <li><a href="{{ route('saran') }}" class="{{ request()->is('saran') ? 'active' : '' }} awal">Tentang</a>
            </li>


            @if (Auth::user() && Auth::user()->role === 'user')
                <li class="nav-item dropdown">
                    <a class="nav-link  profile" href="#" id="navbarDarkDropdownMenuLink"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if (Auth::user()->foto)
                            <img class="avatar" src="{{ asset('storage/user-img/' . Auth::user()->foto) }}">
                        @else
                            <img class="avatar" src="{{ asset('storage/user-img/default-user.jpg') }}">
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-dark nav-drop" aria-labelledby="navbarDarkDropdownMenuLink">
                        <li>
                            <a class="dropdown-item text-center profile"
                                href="{{ Route('profile', Auth::user()->id) }}">
                                @if (Auth::user()->foto)
                                    <img class="dropdown-avatar"
                                        src="{{ asset('storage/user-img/' . Auth::user()->foto) }}">
                                @else
                                    <img class="dropdown-avatar"
                                        src="{{ asset('storage/user-img/default-user.jpg') }}">
                                @endif
                                <p class="email mb-0" style="font-weight: 500; color:black">{{ Auth::user()->nama }}
                                </p>
                                <p class="email mb-0">{{ Auth::user()->email }}</p>
                            </a>
                        </li>
                        <hr>
                        <li>
                            <a class="dropdown-item text-black profile" style="margin-left: 5px"
                                href="{{ Route('profile.edit', Auth::user()->id) }}">
                                <i class="fa fa-gear"></i> Setting
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-black profile" style="margin-left: 5px"
                                href="{{ route('artikel.trash') }}">
                                <i class="fa fa-trash"></i> Di Hapus
                            </a>
                        </li>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <li>
                                <button class="dropdown-item logout-title profile text-black text-start" type="submit">
                                    <i class="fa fa-power-off"></i> Logout
                                </button>
                            </li>
                        </form>
                    </ul>
                </li>
            @else
                <li><a href="{{ route('login') }}"
                        class="nav-link {{ request()->is('login') ? 'active' : '' }}">Login</a></li>
            @endif
            <li>
                <form class="search" role="search" action="{{ route('search.all') }}" method="GET">
                    <div class="search-field">
                        <label for="search-input" class="visually-hidden">Search</label>
                        <input type="search" id="search-input" class="query-wrapper" name="search"
                            placeholder="Search" value="{{ request('search') }}" />
                    </div>
                    <button type="submit" class="search-field-wrapper" aria-label="Submit search">
                        <div class="icon-magnifyingglass-wrapper">
                            <i class="fa fa-search"></i>
                        </div>
                    </button>
                </form>
            </li>
        </ul>

    </div>
</nav>
</header>
