<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\StudentRaw;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

class Register extends Component
{

    #[Layout('components.layouts.auths')]
    public $full_name;
    public $email;
    public $usn;
    public $password;
    public $password_confirmation;

    // phone/verification
   // public $phone;
   // public $phone_verified = false; // set true after server verification
    public $firebase_uid; // optional: store firebase uid after verification

    protected function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'usn' => 'nullable|string|unique:users,usn|max:100|regex:/^\S+$/',
            'password' => ['required','confirmed', Password::min(8)],
         //   'phone' => 'nullable|string|max:30|unique:users,phone',
        ];
    }

  //  protected $listeners = ['phoneVerified' => 'markPhoneVerified'];

     public function markPhoneVerified($phone, $uid = null)
    {
        // called from frontend (Livewire emit) after server confirmed token
       // $this->phone = $phone;
       // $this->phone_verified = true;
        $this->firebase_uid = $uid;
    }

    public function submit()
    {
        $this->validate();

        if (! $this->usn && ! $this->email) {
            $this->addError('usn', 'Provide either a USN or an email to register.');
            $this->addError('email', 'Provide either a USN or an email to register.');
            return;
        }

        // require phone verification
      /*   if (! $this->phone_verified) {
            $this->addError('phone', 'Please verify your phone number using OTP first.');
            return;
        } */

        // Must provide either usn or email
       /*  if (! $this->usn && ! $this->email) {
            $this->addError('usn', 'Provide either a USN or an email to register.');
            $this->addError('email', 'Provide either a USN or an email to register.');
            return;
        } */

        // If USN provided: confirm that student exists in students_raw
        $student = null;
        if ($this->usn) {
            $student = StudentRaw::where('usn', $this->usn)->first();
            if (! $student) {
                $this->addError('usn', 'USN not found. If you are an alumnus not in system, register with email or contact admin.');
                return;
            }

            // Prevent duplicate User for same USN
            if (User::where('usn', $this->usn)->exists()) {
                $this->addError('usn', 'An account with this USN already exists. Try login or use password reset.');
                return;
            }
        }

        //dd($this->usn);

        // If email provided but also USN present, ensure unique email done by validation
        // Create user
        $user = User::create([
            'name' => $this->full_name,
            'email' => $this->email,
            'usn' => $this->usn,
            'password' => Hash::make($this->password),
            'remember_token' => Str::random(60),
           // 'phone' => $this->phone,
           // 'phone_verified_at' => now(),
        ]);

        // Optional: attach student_raw id to user (if you have fk column)
        if ($student && isset($user->id)) {
            // e.g. $user->student_raw_id = $student->id; $user->save();
            // or create relationship record as per your schema
        }

        // Log the user in
        auth()->login($user, true);

        // Redirect to intended page
        return redirect()->intended('/feeds');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
