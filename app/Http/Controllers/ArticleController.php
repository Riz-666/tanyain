<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function article()
    {
        return view('article');
    }

    public function article_detail()
    {
        return view('article_detail');
    }

    public function create_article()
    {
        return view('create_article');
    }
}
