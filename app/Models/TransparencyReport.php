<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransparencyReport extends Model
{
    use HasFactory;

    protected $table = 'transparency_reports';

    protected $fillable = [
        'title',
        'report_year',
        'category',
        'summary',
        'file_pdf_path',
        'file_size',
        'is_active',
    ];

    protected $casts = [
        'report_year' => 'integer',
        'is_active' => 'boolean',
    ];
}
