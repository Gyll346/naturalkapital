<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'sort_order',
    ];

    public function members()
    {
        return $this->hasMany(TeamMember::class, 'category_id')->orderBy('sort_order');
    }
}
