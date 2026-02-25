<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Livewire\Attributes\Layout;

use Illuminate\Support\Facades\Auth;

class activity extends Component
{
    public $perPage = 5;
    public $loaded = 5; 
   #[Layout('components.layouts.master-alumni')]
    protected $listeners = ['postUpdated' => '$refresh'];


    public function loadMore()
    {
        $this->loaded += $this->perPage;
    }

    public function render()
    {
        // Base query: eager load user + small comments slice + reaction counts
        $query = Post::query()
            ->with(['user'])
            ->withCount(['comments','reactions'])
            ->latest();

        // FUTURE: filter by followers / alumni profile here
        $posts = $query->take($this->loaded)->get();

        return view('livewire.activity', [
            'posts' => $posts,
            'hasMore' => Post::count() > $this->loaded,
        ]);
    }
}
