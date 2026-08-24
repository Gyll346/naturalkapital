<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $categories = ArticleCategory::all();
        $selectedCategorySlug = $request->query('category');
        
        $query = Article::with(['category', 'author'])->latest();

        if ($selectedCategorySlug) {
            $query->whereHas('category', function ($q) use ($selectedCategorySlug) {
                $q->where('slug', $selectedCategorySlug);
            });
        }

        $articles = $query->paginate(15)->withQueryString();
        $selectedCategory = $selectedCategorySlug ? ArticleCategory::where('slug', $selectedCategorySlug)->first() : null;

        return view('admin.articles.index', compact('articles', 'categories', 'selectedCategory', 'selectedCategorySlug'));
    }

    public function create()
    {
        $categories = ArticleCategory::all();
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:article_categories,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'attachment_pdf' => 'nullable|mimes:pdf|max:10240',
            'status' => 'required|in:draft,published,archived',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('articles/covers', 'public');
        }

        $pdfPath = null;
        if ($request->hasFile('attachment_pdf')) {
            $pdfPath = $request->file('attachment_pdf')->store('articles/documents', 'public');
        }

        $slug = Str::slug($validated['title']) . '-' . strtolower(Str::random(4));

        $article = Article::create([
            'category_id' => $validated['category_id'],
            'author_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'featured_image_path' => $imagePath,
            'attachment_pdf_path' => $pdfPath,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE_ARTICLE',
            'module' => 'ARTICLE',
            'record_id' => $article->id,
            'details' => "Mempublikasikan artikel: {$article->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil disimpan.');
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = ArticleCategory::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:article_categories,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'attachment_pdf' => 'nullable|mimes:pdf|max:10240',
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image_path'] = $request->file('featured_image')->store('articles/covers', 'public');
        }

        if ($request->hasFile('attachment_pdf')) {
            $validated['attachment_pdf_path'] = $request->file('attachment_pdf')->store('articles/documents', 'public');
        }

        if ($article->status !== 'published' && $validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE_ARTICLE',
            'module' => 'ARTICLE',
            'record_id' => $article->id,
            'details' => "Memperbarui artikel: {$article->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $title = $article->title;
        $article->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE_ARTICLE',
            'module' => 'ARTICLE',
            'record_id' => $id,
            'details' => "Menghapus artikel: {$title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
