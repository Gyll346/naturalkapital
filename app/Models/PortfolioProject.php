<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioProject extends Model
{
    use HasFactory;

    protected $table = 'portfolio_projects';

    protected $fillable = [
        'project_title',
        'slug',
        'category',
        'location',
        'partner_donor',
        'period',
        'summary',
        'description',
        'image_cover_path',
        'document_pdf_path',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
