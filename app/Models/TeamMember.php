<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'full_name',
        'position',
        'bio',
        'photo_path',
        'linkedin_url',
        'email',
        'sort_order',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(TeamCategory::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
