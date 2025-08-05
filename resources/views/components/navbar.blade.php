<nav class="navbar fixed-top">
    <div class="container">
        <div class="logo">TanyaIn</div>

        <button class="hamburger" onclick="toggleNavbar()">☰</button>

        <ul class="nav-links" id="navLinks">
            <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a></li>
            <li><a href="{{ route('repository') }}"
                    class="{{ request()->is('repository') ? 'active' : '' }}">Repositori</a></li>
            <li><a href="{{ route('article') }}" class="{{ request()->is('article') ? 'active' : '' }}">Artikel</a></li>

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
                        <a href="{{ route('profile', Auth::user()->id) }}">
                            <li class="dropdown-item text-center" >
                                @if (Auth::user()->foto)
                                    <img class="avatar dropdown-avatar mb-2"
                                        src="{{ asset('storage/user-img/' . Auth::user()->foto) }}">
                                @else
                                    <img class="avatar dropdown-avatar mb-2"
                                        src="{{ asset('storage/user-img/default-user.jpg') }}">
                                @endif
                                <p class="email mb-0">{{ Auth::user()->nama }}</p>
                                <p class="email mb-0">{{ Auth::user()->username }}</p>
                                <p class="email mb-0">{{ Auth::user()->email }}</p>
                            </li>
                        </a>
                        <li>
                            <hr>
                        </li>
                        <li>
                            <a class="dropdown-item edit-title text-start" style="margin-left: 5px"
                                href="{{ route('profile.edit', Auth::user()->id) }}"><i class="fa fa-gear"></i>
                                Setting</a>
                        </li>

                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item logout-title w-100 text-start" type="submit">
                                    <i class="fa fa-power-off"></i> Logout
                                </button>
                            </form>
                        </li>
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
