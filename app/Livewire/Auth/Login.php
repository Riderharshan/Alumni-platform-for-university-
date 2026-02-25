<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

class Login extends Component
{
    #[Layout('components.layouts.auths')]
    
    public $identifier; // usn or email
    public $password;
    public $remember = false;

    protected $rules = [
        'identifier' => 'required|string',
        'password' => 'required|string',
    ];

    public function submit()
    {
        $this->validate();

        // Optional: simple rate limiter key (identifier + ip)
        $key = 'login-attempts:' . Str::lower($this->identifier ?? 'guest') . ':' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 7)) {
            // You can choose message or throw Lockout event
            event(new Lockout(request()));
            throw ValidationException::withMessages([
                'identifier' => ['Too many login attempts. Please try again later.'],
            ]);
        }

        // Determine whether input is email (contains @) else treat as USN
        if (filter_var($this->identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $this->identifier, 'password' => $this->password];

            if (Auth::attempt($credentials, $this->remember)) {
                RateLimiter::clear($key);
                session()->regenerate();
                return redirect()->intended('/feeds');
            }

            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'password' => ['The provided credentials are incorrect.'],
            ]);
        }

        // treat input as USN (normalize)
        $usn = Str::upper(trim($this->identifier));
        $user = User::where('usn', $usn)->first();

        if (! $user || ! Hash::check($this->password, $user->password)) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'identifier' => ['No account found for this USN or the password is incorrect.'],
            ]);
        }

        // successful: login
        Auth::login($user, $this->remember);
        RateLimiter::clear($key);
        session()->regenerate();

        return redirect()->intended('/feeds');
    }

    public function render(): mixed
    {
        return view('livewire.auth.login');
    }
}
