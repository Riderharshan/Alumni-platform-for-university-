<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumniProfile extends Model
{
    use HasFactory;

    protected $table = 'alumni_profiles';
    public $timestamps = false;

    protected $fillable = [
        'user_id','usn','display_name','first_name','last_name','batch_year',
        'course_id','department_id','selected_professor_id','headline','about',
        'location_city','location_state','location_country','latitude','longitude',
        'is_mentor','mentor_categories','profile_picture','profile_completed','visibility',
        'extra','privacy_settings'
    ];

    protected $casts = [
        'mentor_categories' => 'array',
        'extra' => 'array',
        'privacy_settings' => 'array',
        'is_mentor' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function selectedProfessor()
    {
        return $this->belongsTo(Professor::class, 'selected_professor_id');
    }

    public function education()
    {
        return $this->hasMany(EducationHistory::class, 'profile_id');
    }

    public function experience()
    {
        return $this->hasMany(ExperienceHistory::class, 'profile_id');
    }
}
