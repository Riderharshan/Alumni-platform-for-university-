<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Department;
use App\Models\Course;
use App\Models\AlumniProfile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;


class AlumniProfileForm extends Component
{
    use WithFileUploads;
        #[Layout('components.layouts.master-alumni-profile')]
    public ?AlumniProfile $profile = null;

    public $usn;
    public $display_name;
    public $first_name;
    public $last_name;
    public $batch_year;
    public $department_id;
    public $course_id;
    public $selected_professor_id;
    public $headline;
    public $about;
    public $location_city;
    public $location_state;
    public $location_country;
    public $latitude;
    public $longitude;
    public $is_mentor = false;
    public $mentor_categories = []; // array of strings
    public $profile_picture; // temporary upload
    public $visibility = 'alumni_only';
    public $extra = [];
    public $privacy_settings = [];



    // lists for selects
    public $departments = [];
    public $courses = [];
    public $mentorCategoriesOptions = [
        'career', 'resume', 'entrepreneurship', 'tech', 'interview',
    ];

    protected $listeners = [
        'setLocation' => 'setLocationFromJs',
    ];

    public function mount()
    {
        $this->departments = Department::orderBy('name')->get();
        $this->courses = collect();

        $user = Auth::user();

        // Load or init profile
        $this->profile = AlumniProfile::firstWhere('user_id', $user->id);

        if ($this->profile) {
            $this->fillFromProfile();
            // Ensure the courses list matches current department
            if ($this->department_id) {
                $this->courses = Course::where('department_id', $this->department_id)->orderBy('name')->get();
            }
        } else {
            // defaults
            $this->visibility = 'alumni_only';
            $this->mentor_categories = [];
            $this->privacy_settings = [];
        }

        if (!is_array($this->privacy_settings)) {
            $this->privacy_settings = [
                'show_email' => true,
                'show_phone' => false,
                'show_location' => true,
                'show_mentor_status' => true,
            ];
        }

        $this->privacy_settings['department'] = $this->privacy_settings['department'] ?? 'alumni_only';
$this->privacy_settings['course'] = $this->privacy_settings['course'] ?? 'alumni_only';
$this->privacy_settings['email'] = $this->privacy_settings['email'] ?? 'alumni_only';
$this->privacy_settings['batch_year'] = $this->privacy_settings['batch_year'] ?? 'alumni_only';
$this->privacy_settings['location'] = $this->privacy_settings['location'] ?? 'alumni_only';
$this->privacy_settings['address']  = $this->privacy_settings['address']  ?? 'alumni_only';
$this->privacy_settings['headline'] = $this->privacy_settings['headline'] ?? 'public';
$this->privacy_settings['about']    = $this->privacy_settings['about']    ?? 'alumni_only';
$this->privacy_settings['phone']    = $this->privacy_settings['phone']    ?? 'alumni_only';




    }

    protected function rules()
    {
        $userId = Auth::id();

        return [
            'usn' => ['nullable','string','max:30'],
            'display_name' => ['nullable','string','max:191'],
            'first_name' => ['nullable','string','max:100'],
            'last_name' => ['nullable','string','max:100'],
            'batch_year' => ['nullable','integer','between:1900,' . (date('Y')+1)],
            'department_id' => ['nullable','integer', Rule::exists('departments', 'id')],
            'course_id' => ['nullable','integer', Rule::exists('courses', 'id')],
            'selected_professor_id' => ['nullable','integer', Rule::exists('professors', 'id')],
            'headline' => ['nullable','string','max:191'],
            'about' => ['nullable','string'],
            'location_city' => ['nullable','string','max:100'],
            'location_state' => ['nullable','string','max:100'],
            'location_country' => ['nullable','string','max:100'],
            'latitude' => ['nullable','numeric'],
            'longitude' => ['nullable','numeric'],
            'is_mentor' => ['boolean'],
            'mentor_categories' => ['nullable','array'],
            'mentor_categories.*' => ['string','max:100'],
            'profile_picture' => ['nullable','image','max:5120'], // 5MB
            'visibility' => ['required', Rule::in(['public','alumni_only','private'])],
            'extra' => ['nullable','array'],
            'privacy_settings' => ['nullable','array'],
            'privacy_settings.show_email' => ['boolean'],
            'privacy_settings.show_phone' => ['boolean'],
            'privacy_settings.show_location' => ['boolean'],
            'privacy_settings.show_mentor_status' => ['boolean'],

        ];
    }

    public function updatedDepartmentId($value)
    {
        // fetch courses for the selected department
        $this->courses = Course::where('department_id', $value)->orderBy('name')->get();
        // reset selected course if not in list
        if ($this->course_id && !$this->courses->contains('id', $this->course_id)) {
            $this->course_id = null;
        }
    }
    

  #[On('setLocation')]
public function setLocationFromJs($lat, $lng)
{
    $this->latitude = $lat;
    $this->longitude = $lng;
}


    public function fillFromProfile()
    {
        $p = $this->profile;
        $this->usn = $p->usn;
        $this->display_name = $p->display_name;
        $this->first_name = $p->first_name;
        $this->last_name = $p->last_name;
        $this->batch_year = $p->batch_year;
        $this->department_id = $p->department_id;
        $this->course_id = $p->course_id;
        $this->selected_professor_id = $p->selected_professor_id;
        $this->headline = $p->headline;
        $this->about = $p->about;
        $this->location_city = $p->location_city;
        $this->location_state = $p->location_state;
        $this->location_country = $p->location_country;
        $this->latitude = $p->latitude;
        $this->longitude = $p->longitude;
        $this->is_mentor = (bool)$p->is_mentor;
        $this->mentor_categories = $p->mentor_categories ? json_decode($p->mentor_categories, true) : [];
        $this->visibility = $p->visibility ?? 'alumni_only';
        $this->extra = $p->extra ? : [];
        $this->privacy_settings = $p->privacy_settings ?  : [];
    }

    public function saveProfile()
    {
        $this->validate(); 

        $user = Auth::user();

        $data = [
            'user_id' => $user->id,
            'usn' => $this->usn,
            'display_name' => $this->display_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'batch_year' => $this->batch_year,
            'course_id' => $this->course_id,
            'department_id' => $this->department_id,
            'selected_professor_id' => $this->selected_professor_id,
            'headline' => $this->headline,
            'about' => $this->about,
            'location_city' => $this->location_city,
            'location_state' => $this->location_state,
            'location_country' => $this->location_country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_mentor' => $this->is_mentor ? 1 : 0,
            'mentor_categories' => $this->mentor_categories ? json_encode(array_values($this->mentor_categories)) : null,
            'visibility' => $this->visibility,
            'extra' => $this->extra ?: null,
            'privacy_settings' => $this->privacy_settings ?: null,
        ];

        if ($this->profile) {
            // update
            $this->profile->update($data);
        } else {
            $this->profile = AlumniProfile::create($data);
        }

        // handle picture upload
        if ($this->profile_picture) {
            // delete old if exists
            if ($this->profile->profile_picture) {
                Storage::disk(config('filesystems.default'))->delete($this->profile->profile_picture);
            }

            $path = $this->profile_picture->storePublicly('alumni_profile_pictures', config('filesystems.default'));
            $this->profile->profile_picture = $path;
            $this->profile->save();
        }

        session()->flash('success', 'Profile saved successfully.');
        $this->dispatch('profileSaved'); // optional: listen in UI
    }

    public function render()
    {
        return view('livewire.alumni-profile-form');
    }
}
