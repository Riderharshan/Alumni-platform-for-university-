<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'post_type', 'title', 'body', 'metadata', 'is_published', 'is_approved', 'visibility'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_published' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class, 'post_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function AlumniProfile(): BelongsTo
    {
        return $this->belongsTo(AlumniProfile::class);
    }

    // comments (top-level + threaded via parent_id)
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at','asc');
    }

    // reports polymorphic
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable', 'reportable_type', 'reportable_id');
    }

    // reactions polymorphic
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable', 'reactable_type', 'reactable_id');
    }

    public function scopeActiveStories($query)
    {
        return $query->stories()
            ->where('is_published', true)
            ->where('created_at', '>=', now()->subDay());
    }


    // App\Models\Post.php
/* 
public function scopeStories($q)
{
    return $q->where('post_type', 'story');
}

public function scopeActive($q)
{
    // visible for 24 hours from creation
    return $q->where('created_at', '>=', now()->subDay());
}

public function scopePublishedApproved($q)
{
    return $q->where('is_published', true)->where('is_approved', true);
}

 public function scopeActiveStories($q)
    {
        return $q->where('post_type', 'story')
            ->where('is_published', true)
            ->where('created_at', '>=', now()->subDay())
            ->whereHas('media', fn ($m) => $m->where('type', 'image'));
    }

    /** Eager load media in display order */
   /*  public function scopeWithStoryMedia($q)
    {
        return $q->with(['media' => fn ($m) => $m->where('type', 'image')->orderBy('position')]);
    } */ 


}
