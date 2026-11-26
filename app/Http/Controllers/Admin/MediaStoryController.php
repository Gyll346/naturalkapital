<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaStory;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;

class MediaStoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = MediaStory::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('caption', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        $stories = $query->orderBy('sort_order', 'asc')->paginate(20)->withQueryString();
        return view('admin.media_stories.index', compact('stories'));
    }

    public function create()
    {
        return view('admin.media_stories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'media_type' => 'required|in:photo,video',
            'category' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:150',
            'caption' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'video_file' => 'nullable|mimes:mp4,mov,ogg,webm,m4v|max:102400',
            'youtube_url' => 'nullable|string|max:500',
            'photographer_credits' => 'nullable|string|max:150',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $videoUrl = $validated['youtube_url'] ?? null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('stories/videos', 'public');
            $videoUrl = '/storage/' . $videoPath;
        }

        $paths = $this->processPhotoUploads($request, $validated['media_type']);

        $story = MediaStory::create([
            'title' => $validated['title'],
            'media_type' => $validated['media_type'],
            'category' => $validated['category'] ?? 'Dokumentasi Lapangan',
            'location' => $validated['location'] ?? null,
            'caption' => $validated['caption'] ?? null,
            'image_path' => $paths[0] ?? null,
            'gallery_images' => count($paths) > 1 ? array_slice($paths, 1) : null,
            'youtube_url' => $videoUrl,
            'photographer_credits' => $validated['photographer_credits'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_MEDIA_STORY',
            'module' => 'Story Media',
            'details' => 'Menambahkan story media: ' . $story->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.media-stories.index')->with('success', 'Story media foto/video berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $story = MediaStory::findOrFail($id);
        return view('admin.media_stories.edit', compact('story'));
    }

    public function update(Request $request, $id)
    {
        $story = MediaStory::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'media_type' => 'required|in:photo,video',
            'category' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:150',
            'caption' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'video_file' => 'nullable|mimes:mp4,mov,ogg,webm,m4v|max:102400',
            'youtube_url' => 'nullable|string|max:500',
            'photographer_credits' => 'nullable|string|max:150',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('images') || $request->hasFile('image')) {
            $this->deleteStoredPhotos($story->photo_paths);
            $paths = $this->processPhotoUploads($request, $validated['media_type']);
            $story->image_path = $paths[0] ?? null;
            $story->gallery_images = count($paths) > 1 ? array_slice($paths, 1) : null;
        }

        $videoUrl = $story->youtube_url;
        if ($request->hasFile('video_file')) {
            if ($story->youtube_url && str_starts_with($story->youtube_url, '/storage/')) {
                $oldPath = ltrim(str_replace('/storage/', '', $story->youtube_url), '/');
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $videoPath = $request->file('video_file')->store('stories/videos', 'public');
            $videoUrl = '/storage/' . $videoPath;
        } elseif ($request->filled('youtube_url')) {
            $videoUrl = $validated['youtube_url'];
        }

        $story->update([
            'title' => $validated['title'],
            'media_type' => $validated['media_type'],
            'category' => $validated['category'] ?? 'Dokumentasi Lapangan',
            'location' => $validated['location'] ?? null,
            'caption' => $validated['caption'] ?? null,
            'youtube_url' => $videoUrl,
            'photographer_credits' => $validated['photographer_credits'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_MEDIA_STORY',
            'module' => 'Story Media',
            'details' => 'Memperbarui story media: ' . $story->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.media-stories.index')->with('success', 'Story media foto/video berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $story = MediaStory::findOrFail($id);

        $this->deleteStoredPhotos($story->photo_paths);

        if ($story->youtube_url && str_starts_with($story->youtube_url, '/storage/')) {
            $oldPath = ltrim(str_replace('/storage/', '', $story->youtube_url), '/');
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $title = $story->title;
        $story->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_MEDIA_STORY',
            'module' => 'Story Media',
            'details' => 'Menghapus story media: ' . $title,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back(fallback: route('admin.media-stories.index'))->with('success', 'Story media foto/video berhasil dihapus!');
    }

    protected function processPhotoUploads(Request $request, string $mediaType): array
    {
        $files = $request->file('images') ?? ($request->hasFile('image') ? [$request->file('image')] : []);
        if ($mediaType === 'video') {
            $files = array_slice($files, 0, 1);
        }

        return array_map(fn ($file) => $this->compressAndSavePhoto($file), $files);
    }

    protected function deleteStoredPhotos(array $paths): void
    {
        foreach ($paths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * Kompres dan simpan foto dokumentasi lapangan (WebP/JPEG, max lebar 1400px)
     */
    protected function compressAndSavePhoto($file, $maxWidth = 1400, $maxHeight = 900, $quality = 82): string
    {
        $destinationDir = storage_path('app/public/stories/photos');
        if (!file_exists($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        if (!extension_loaded('gd') || !function_exists('imagecreatetruecolor')) {
            return $file->store('stories/photos', 'public');
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
            return $file->store('stories/photos', 'public');
        }

        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        // Resize proporsional menjaga aspek rasio foto dokumentasi
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight, 1.0);
        $targetWidth = (int) round($origWidth * $ratio);
        $targetHeight = (int) round($origHeight * $ratio);

        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Pertahankan transparansi bila foto PNG
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

        // Konversi ke WebP jika server mendukung, fallback ke JPEG
        if (function_exists('imagewebp')) {
            $filename = uniqid('story_', true) . '.webp';
            $targetFile = $destinationDir . '/' . $filename;
            imagewebp($targetImage, $targetFile, $quality);
        } else {
            $filename = uniqid('story_', true) . '.jpg';
            $targetFile = $destinationDir . '/' . $filename;
            imagejpeg($targetImage, $targetFile, $quality);
        }

        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        return 'stories/photos/' . $filename;
    }
}
