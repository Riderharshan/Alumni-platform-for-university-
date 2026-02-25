<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reporter_id','reportable_type','reportable_id','reason','details','status','assigned_admin_id','admin_notes','action_taken'
    ];

    public function reporter() { return $this->belongsTo(User::class,'reporter_id'); }

    public function reportable()
    {
        return $this->morphTo(null,'reportable_type','reportable_id');
    }
}
