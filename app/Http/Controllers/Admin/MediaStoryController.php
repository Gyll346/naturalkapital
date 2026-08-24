<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaStory;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;

class MediaStoryController extends Controller
{
    public function index()
    {
        $stories = MediaStory::orderBy('sort_order', 'asc')->paginate(20);
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
            'youtube_url' => 'nullable|string|max:255',
            'photographer_credits' => 'nullable|string|max:150',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stories/photos', 'public');
        }

        $story = MediaStory::create([
            'title' => $validated['title'],
            'media_type' => $validated['media_type'],
            'category' => $validated['category'] ?? 'Dokumentasi Lapangan',
            'location' => $validated['location'],
            'caption' => $validated['caption'],
            'image_path' => $imagePath,
            'youtube_url' => $validated['youtube_url'],
            'photographer_credits' => $validated['photographer_credits'],
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
            'youtube_url' => 'nullable|string|max:255',
            'photographer_credits' => 'nullable|string|max:150',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($story->image_path) {
                Storage::disk('public')->delete($story->image_path);
            }
            $story->image_path = $request->file('image')->store('stories/photos', 'public');
        }

        $story->update([
            'title' => $validated['title'],
            'media_type' => $validated['media_type'],
            'category' => $validated['category'] ?? 'Dokumentasi Lapangan',
            'location' => $validated['location'],
            'caption' => $validated['caption'],
            'youtube_url' => $validated['youtube_url'],
            'photographer_credits' => $validated['photographer_credits'],
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

        if ($story->image_path) {
            Storage::disk('public')->delete($story->image_path);
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

        return redirect()->route('admin.media-stories.index')->with('success', 'Story media foto/video berhasil dihapus!');
    }
}
