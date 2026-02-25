<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRaw extends Model
{
    use HasFactory;

    protected $table = 'students_raw';
    public $timestamps = false;

    protected $fillable = [
        'usn','full_name','batch_year','course','department',
        'date_of_birth','mobile','email','gender','address',
        'blood_group','extra','user_id'
    ];

    protected $casts = [
        'extra' => 'array',
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
