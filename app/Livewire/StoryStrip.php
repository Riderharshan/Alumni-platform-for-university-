<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class StoryStrip extends Component
{
    public $stories = [];

    protected $listeners = [
        'postSaved' => 'refreshStories', // from PostForm
    ];

    public function mount()
    {
        $this->loadStories();
    }

    public function loadStories()
    {
        $query = Post::activeStories()
            ->with(['user.alumniProfile', 'media'])
            ->orderByDesc('created_at');

        $stories = $query->get();

        // Respect profile visibility helper
        $this->stories = $stories->filter(function ($story) {
            $profile = optional($story->user)->alumniProfile;
            if (! $profile) {
                return false;
            }

            // if your can_view_profile helper is global:
            return can_view_profile($profile);
        })->values();
    }

    public function refreshStories()
    {
        $this->loadStories();
    }

    public function openStory(int $storyId)
    {
        // Ask StoryViewer to load & show this story
        $this->dispatch('openStoryModal', storyId: $storyId);
    }

    public function render()
    {
        return view('livewire.story-strip');
    }
}
