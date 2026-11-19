<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'record_id',
        'details',
        'ip_address',
        'user_agent',
    ];

    protected static function booted()
    {
        static::creating(function ($log) {
            if (in_array($log->ip_address, ['127.0.0.1', '::1']) || preg_match('/^172\.(1[6-9]|2[0-9]|3[0-1])\./', $log->ip_address ?? '')) {
                $log->ip_address = '127.0.0.1';
            }
        });
    }

    public function getFormattedIpAttribute(): string
    {
        $ip = $this->ip_address;
        if (empty($ip) || in_array($ip, ['127.0.0.1', '::1']) || preg_match('/^172\.(1[6-9]|2[0-9]|3[0-1])\./', $ip)) {
            return '127.0.0.1 (Localhost)';
        }
        return $ip;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
