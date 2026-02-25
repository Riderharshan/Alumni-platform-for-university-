<?php


namespace App\Livewire;


use Livewire\Component;
use App\Models\AlumniProfile;


class AlumniMap extends Component
{
/** @var array<int, array{id:int,name:string,lat:float,lng:float,avatar:string|null,profile_url:string|null}> */
public array $points = [];


public function mount(): void
{
// Adjust relations/fields to your app
$profiles = AlumniProfile::query()
->whereNotNull('latitude')
->whereNotNull('longitude')
->with(['user:id,name']) // tweak if your column differs
->get();


$this->points = $profiles->map(function ($p) {
$user = $p->user;


// If you have Jetstream or your own accessor:
$avatar = method_exists($user, 'getProfilePhotoUrlAttribute') || isset($user->profile_photo_url)
? ($user->profile_photo_url ?? null)
: (isset($user->profile_photo) && $user->profile_photo
? \Storage::url($user->profile_photo)
: asset('images/default-avatar.png'));


return [
'id' => (int) $p->id,
'name' => (string) ($user->name ?? 'Unknown'),
'lat' => (float) $p->latitude,
'lng' => (float) $p->longitude,
'avatar' => $avatar,
// If you have a profile route, plug it here; otherwise keep null
'profile_url' => function_exists('route') && $user ? route('alumni.view', $user) : null,
];
})->values()->all();
}


public function render()
{
return view('livewire.alumni-map');
}
}