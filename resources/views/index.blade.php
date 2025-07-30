@extends('components.app')
@section('body')
    <header class="header">
        <h1 class="text-wrapper">TanyaIn</h1>
        <p class="div">PLATFORM BLOG DAN ARTIKEL AKADEMIK BERBASIS PENCARIAN</p>
        <a href="#" class="button btn btn-light" role="button">
            <span class="text-wrapper-2">JELAJAHI ARTIKEL</span>
        </a>
    </header>
    <main class="frame">
        <section class="listbox-choose-blog">
            <div class="overlap-group">
                <div class="border" aria-hidden="true"></div>
                <div class="option-all-blogs">
                    <div class="container">
                        <select class="text-wrapper" aria-label="Choose blog category">
                            <option selected>All blogs</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>
        <article class="background-border">
            <h2 class="link-a-better">Cara Handle Laravel Error 500</h2>
            <p class="disini-kita-akan">Disini kita akan<br />jelaskan error 500<br />dan cara handle nya</p>
            <div class="attachment"> <img class="vector" src="img/vector.svg" alt="PDF icon" /> <span
                    class="text-wrapper-2">Laravel_Error_500.pdf</span> </div>
            <footer class="background">
                <p class="p">dibuat Jul 20, 2010 at 22:27</p>
                <div class="author-info"> <img class="topchef-s-user" src="img/image.png" alt="Mas Agung's avatar" />
                    <div class="author-details"> <span class="text-wrapper-3">Mas Agung</span> <span
                            class="text-wrapper-4">15.8k</span> </div>
                </div>
            </footer>
        </article>
        <article class="background-border">
            <h2 class="link-a-better">Cara Handle Laravel Error 500</h2>
            <div class="attachment"> <img class="vector" src="img/vector.svg" alt="PDF icon" /> <span
                    class="text-wrapper-2">Laravel_Error_500.pdf</span> </div>
            <p class="disini-kita-akan">Disini kita akan<br />jelaskan error 500<br />dan cara handle nya</p>
            <footer class="background">
                <p class="p">dibuat Jul 20, 2010 at 22:27</p>
                <div class="author-info">
                    <img class="topchef-s-user-2"
                        src="https://tse3.mm.bing.net/th/id/OIP.4JF4xKSznSNzZHh1wEW_9gHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3"
                        alt="Mas Agung's avatar" />
                    <div class="author-details">
                        <span class="text-wrapper-3">Mas Agung</span>
                        <span class="text-wrapper-4">15.8k</span>
                    </div>
                </div>
            </footer>
        </article>
    @endsection
