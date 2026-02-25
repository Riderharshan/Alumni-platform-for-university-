<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = ['user_id','reactable_type','reactable_id','reaction_type'];
    public $timestamps = true;

    public function user() { return $this->belongsTo(User::class); }

    public function reactable()
    {
        return $this->morphTo(null, 'reactable_type', 'reactable_id');
    }
}
