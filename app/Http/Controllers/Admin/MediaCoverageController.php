<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MediaCoverageController extends Controller
{
    private function getCategory()
    {
        return ArticleCategory::firstOrCreate(
            ['slug' => 'liputan-media'],
            [
                'category_name' => 'Liputan Media',
                'description' => 'Liputan pers nasional dan internasional mengenai YNKI.'
            ]
        );
    }

    public function index(Request $request)
    {
        $category = $this->getCategory();
        $query = Article::where('category_id', $category->id)->latest();

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $coverages = $query->paginate(15)->withQueryString();

        return view('admin.media_coverages.index', compact('coverages'));
    }

    public function create()
    {
        return view('admin.media_coverages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'media_name' => 'required|string|max:150',
            'external_url' => 'required|url|max:500',
            'excerpt' => 'required|string|max:1000',
            'topic_category' => 'nullable|string|max:100',
        ]);

        $category = $this->getCategory();
        $slug = Str::slug($validated['title']) . '-' . strtolower(Str::random(4));

        // Format content as clean JSON containing media metadata
        $contentData = [
            'media_name' => $validated['media_name'],
            'external_url' => $validated['external_url'],
            'topic_category' => $validated['topic_category'] ?? 'Liputan Media',
        ];

        $article = Article::create([
            'category_id' => $category->id,
            'author_id' => Auth::id() ?? 1,
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'],
            'content' => json_encode($contentData),
            'status' => 'published',
            'published_at' => now(), // Otomatis hari/waktu saat dibuat
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE_MEDIA_COVERAGE',
            'module' => 'LIPUTAN_MEDIA',
            'record_id' => $article->id,
            'details' => "Menambahkan liputan media: {$article->title} ({$validated['media_name']})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.media-coverages.index')->with('success', 'Liputan media berhasil ditambahkan dan diterbitkan!');
    }

    public function edit($id)
    {
        $category = $this->getCategory();
        $coverage = Article::where('category_id', $category->id)->findOrFail($id);

        $mediaData = json_decode($coverage->content, true) ?: [];

        return view('admin.media_coverages.edit', compact('coverage', 'mediaData'));
    }

    public function update(Request $request, $id)
    {
        $category = $this->getCategory();
        $coverage = Article::where('category_id', $category->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'media_name' => 'required|string|max:150',
            'external_url' => 'required|url|max:500',
            'excerpt' => 'required|string|max:1000',
            'topic_category' => 'nullable|string|max:100',
        ]);

        $contentData = [
            'media_name' => $validated['media_name'],
            'external_url' => $validated['external_url'],
            'topic_category' => $validated['topic_category'] ?? 'Liputan Media',
        ];

        $coverage->update([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => json_encode($contentData),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE_MEDIA_COVERAGE',
            'module' => 'LIPUTAN_MEDIA',
            'record_id' => $coverage->id,
            'details' => "Memperbarui liputan media: {$coverage->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.media-coverages.index')->with('success', 'Liputan media berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $category = $this->getCategory();
        $coverage = Article::where('category_id', $category->id)->findOrFail($id);
        $title = $coverage->title;
        $coverage->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE_MEDIA_COVERAGE',
            'module' => 'LIPUTAN_MEDIA',
            'record_id' => $id,
            'details' => "Menghapus liputan media: {$title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.media-coverages.index')->with('success', 'Liputan media berhasil dihapus.');
    }
}
