<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'slug',
        'file_size',
        'media_id',
        'timestamp',
        'media_url',
        'dedup_id',
        'resolution',
        'rip_type',
        'codec',
        'user_id'
    ];

    /**
     * Get the route key for the model (use slug instead of id).
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the user that owns the file.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
