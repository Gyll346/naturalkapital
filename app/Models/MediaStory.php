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
        'youtube_url',
        'photographer_credits',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

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
}
