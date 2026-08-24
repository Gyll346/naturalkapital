<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleCategory;

class PublicArticleController extends Controller
{
    public function index(Request $request)
    {
        $categories = ArticleCategory::all();
        $selectedCategory = $request->query('kategori');

        $query = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->latest('published_at');

        if ($selectedCategory) {
            $query->whereHas('category', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory);
            });
        }

        $articles = $query->paginate(9);

        return view('public.articles.index', compact('articles', 'categories', 'selectedCategory'));
    }

    public function show($slug)
    {
        $article = Article::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $article->increment('views_count');

        $relatedArticles = Article::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.articles.show', compact('article', 'relatedArticles'));
    }
}
