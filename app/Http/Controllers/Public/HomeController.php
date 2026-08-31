<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\TeamMember;

class HomeController extends Controller
{
    public function index()
    {
        $featuredArticles = Article::with('category')
            ->where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        $keyLeaders = TeamMember::with('category')
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        return view('public.home', compact('featuredArticles', 'keyLeaders'));
    }
}
