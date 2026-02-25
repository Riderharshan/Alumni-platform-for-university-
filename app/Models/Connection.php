<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    protected $table = 'connections';
    public $timestamps = false;

    protected $fillable = ['requester_id','requestee_id','status','requested_at','responded_at'];

    protected $casts = [
        'requested_at' => 'datetime',
        'responded_at' => 'datetime'
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function requestee()
    {
        return $this->belongsTo(User::class, 'requestee_id');
    }
}
