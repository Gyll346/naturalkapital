<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_name',
        'slug',
        'description',
        'target_amount',
        'collected_amount',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'collected_amount' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'program_id');
    }
}
