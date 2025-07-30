<header class="hero-section">
      <nav class="navbar">
        <div class="logo">
          <h1 class="text-wrapper-3">Logo</h1>
        </div>
        <ul class="nav-item">
          <li><a href="#" class="button-repositori">Beranda</a></li>
          <li><a href="#" class="nav-link">Repositori</a></li>
          <li><a href="#" class="nav-link">Article</a></li>
          <li><a href="{{ route('login') }}" class="nav-link">Login</a></li>
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
