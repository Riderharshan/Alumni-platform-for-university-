<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\AlumniProfile;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'usn',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

     public function alumniProfile()
    {
        return $this->hasOne(\App\Models\AlumniProfile::class, 'user_id', 'id');
    }

    // app/Models/User.php
public function hasAcceptedConnectionWith(User $other): bool
{
    return \App\Models\Connection::where('status', 'accepted')
        ->where(function ($q) use ($other) {
            $q->where(function ($q) use ($other) {
                $q->where('user_id', $this->id)
                  ->where('connected_user_id', $other->id);
            })->orWhere(function ($q) use ($other) {
                $q->where('user_id', $other->id)
                  ->where('connected_user_id', $this->id);
            });
        })
        ->exists();
}


    /**
     * Optional: posts relationship if you need it
     */
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class, 'user_id', 'id');
    }
}
