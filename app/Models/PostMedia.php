<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    protected $table = 'post_media';

    public $timestamps = false; // your migration added created_at; adjust if you add updated_at etc.

    protected $fillable = [
        'post_id', 'url', 'type', 'meta', 'disk', 'file_name', 'original_name',
        'mime_type', 'thumbnail_url', 'is_processed', 'position', 'created_at'
    ];

    protected $casts = [
        'meta' => 'array',
        'is_processed' => 'boolean',
    ];

    public function post()
    {
        return $this->belongsTo(\App\Models\Post::class, 'post_id');
    }

    
}
