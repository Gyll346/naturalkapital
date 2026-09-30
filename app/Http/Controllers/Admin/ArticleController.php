<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $categories = ArticleCategory::all();
        $selectedCategorySlug = $request->query('category');
        
        $query = Article::with(['category', 'author'])->latest();

        $search = $request->query('search');

        if ($selectedCategorySlug) {
            $query->whereHas('category', function ($q) use ($selectedCategorySlug) {
                $q->where('slug', $selectedCategorySlug);
            });
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
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
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'attachment_pdf' => 'nullable|mimes:pdf|max:10240',
            'status' => 'required|in:draft,published,archived',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $this->compressAndSaveCover($request->file('featured_image'));
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
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'attachment_pdf' => 'nullable|mimes:pdf|max:10240',
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($article->featured_image_path && Storage::disk('public')->exists($article->featured_image_path)) {
                Storage::disk('public')->delete($article->featured_image_path);
            }
            $validated['featured_image_path'] = $this->compressAndSaveCover($request->file('featured_image'));
        }

        if ($request->hasFile('attachment_pdf')) {
            if ($article->attachment_pdf_path && Storage::disk('public')->exists($article->attachment_pdf_path)) {
                Storage::disk('public')->delete($article->attachment_pdf_path);
            }
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

        if ($article->featured_image_path && Storage::disk('public')->exists($article->featured_image_path)) {
            Storage::disk('public')->delete($article->featured_image_path);
        }
        if ($article->attachment_pdf_path && Storage::disk('public')->exists($article->attachment_pdf_path)) {
            Storage::disk('public')->delete($article->attachment_pdf_path);
        }

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

    /**
     * Kompres dan simpan gambar sampul artikel (WebP/JPEG, max lebar 1200px)
     */
    protected function compressAndSaveCover($file, $maxWidth = 1200, $maxHeight = 800, $quality = 82): string
    {
        $destinationDir = storage_path('app/public/articles/covers');
        if (!file_exists($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        if (!extension_loaded('gd') || !function_exists('imagecreatetruecolor')) {
            return $file->store('articles/covers', 'public');
        }

        $sourcePath = $file->getRealPath();
        $mime = $file->getMimeType();

        $sourceImage = null;
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $sourceImage = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = @imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    $sourceImage = @imagecreatefromwebp($sourcePath);
                }
                break;
        }

        if (!$sourceImage) {
            return $file->store('articles/covers', 'public');
        }

        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        // Hitung proporsi resize
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight, 1.0);
        $targetWidth = (int) round($origWidth * $ratio);
        $targetHeight = (int) round($origHeight * $ratio);

        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Pertahankan transparansi untuk PNG/WebP
        imagealphablending($targetImage, false);
        imagesavealpha($targetImage, true);
        $transparent = imagecolorallocatealpha($targetImage, 255, 255, 255, 127);
        imagefilledrectangle($targetImage, 0, 0, $targetWidth, $targetHeight, $transparent);

        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0, 0, 0, 0,
            $targetWidth,
            $targetHeight,
            $origWidth,
            $origHeight
        );

        // Simpan sebagai WebP (paling optimal & ringan) atau JPG
        if (function_exists('imagewebp')) {
            $filename = uniqid('art_', true) . '.webp';
            $targetFile = $destinationDir . '/' . $filename;
            imagewebp($targetImage, $targetFile, $quality);
        } else {
            $filename = uniqid('art_', true) . '.jpg';
            $targetFile = $destinationDir . '/' . $filename;
            imagejpeg($targetImage, $targetFile, $quality);
        }

        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        return 'articles/covers/' . $filename;
    }
}
