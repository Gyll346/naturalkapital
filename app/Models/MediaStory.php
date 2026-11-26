<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaStory extends Model
{
    use HasFactory;

    protected $table = 'media_stories';

    protected $fillable = [
        'title',
        'media_type',
        'category',
        'location',
        'caption',
        'image_path',
        'gallery_images',
        'youtube_url',
        'photographer_credits',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getPhotoPathsAttribute(): array
    {
        $paths = array_merge(
            $this->image_path ? [$this->image_path] : [],
            $this->gallery_images ?? []
        );

        return array_values(array_unique(array_filter($paths)));
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return '/storage/' . ltrim($this->image_path, '/');
        }

        if ($this->youtube_url) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->youtube_url, $match)) {
                return 'https://img.youtube.com/vi/' . $match[1] . '/hqdefault.jpg';
            }
        }

        return null;
    }

    public function getVideoPlatformAttribute(): string
    {
        $url = $this->youtube_url ?? '';
        if (preg_match('/(youtube\.com|youtu\.be)/i', $url)) {
            return 'youtube';
        }
        if (preg_match('/instagram\.com/i', $url)) {
            return 'instagram';
        }
        if (preg_match('/(facebook\.com|fb\.watch)/i', $url)) {
            return 'facebook';
        }
        if (preg_match('/tiktok\.com/i', $url)) {
            return 'tiktok';
        }
        if (preg_match('/\.(mp4|webm|ogg|mov)$/i', $url) || str_starts_with($url, '/storage/')) {
            return 'upload';
        }
        return 'link';
    }

    public function getVideoLabelAttribute(): string
    {
        return match ($this->video_platform) {
            'youtube' => 'Tonton di YouTube',
            'instagram' => 'Tonton di Instagram',
            'facebook' => 'Tonton di Facebook',
            'tiktok' => 'Tonton di TikTok',
            'upload' => 'Putar Video',
            default => 'Tonton Video',
        };
    }
}
