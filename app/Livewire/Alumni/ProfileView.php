<?php


namespace App\Livewire\Alumni;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Post;
use Livewire\Attributes\Layout;


class ProfileView extends Component
{
    use WithPagination;
     #[Layout('components.layouts.master-alumni-profile')]
    public $perPage = 5;
    public $loaded = 5;
    public $userId;
    public $user;

    protected $paginationTheme = 'bootstrap'; // or 'tailwind', depends on your setup

    public function mount($userId)
    {
        $this->userId = $userId;

        // eager load user + alumni profile
        $this->user = User::with('alumniProfile')->findOrFail($userId);
    }

    public function loadMore()
    {
        $this->loaded += $this->perPage;
    }

    

    public function render()
    {
        $posts = Post::with(['user.alumniProfile'])
            ->where('user_id', $this->userId)
            ->latest()
            ->paginate(5);

         // dd($posts->user->alumniProfile);

      //  dd($this->user->alumniProfile->extra);

      /*   $viewer = auth()->user();       // who is looking
        $owner  = $user;                 // profile owner

        $isOwner      = $viewer && $viewer->id === $owner->id;
        $isConnected  = $viewer ? $viewer->hasAcceptedConnectionWith($owner) : false;

        $visibility   = $owner->alumniProfile->visibility ?? 'public';

        $canSee = match ($visibility) {
            'public'       => true,
            'alumni_only'  => $isOwner || $isConnected,
            'private'      => $isOwner,
            default        => false,
        }; */


        return view('livewire.alumni.profile-view', [
            'user' => $this->user,
            'posts' => $posts,
            'hasMore' => Post::count() > $this->loaded,
        ]);
    }
}
