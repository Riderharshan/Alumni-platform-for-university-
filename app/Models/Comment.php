<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['post_id','user_id','parent_id','body'];

    public function post() { return $this->belongsTo(Post::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function parent() { return $this->belongsTo(self::class, 'parent_id'); }
    public function children() { return $this->hasMany(self::class, 'parent_id'); }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable', 'reportable_type', 'reportable_id');
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable', 'reactable_type', 'reactable_id');
    }
}
