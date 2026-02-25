<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;

class PostCard extends Component
{
    // model passed from parent
    public Post $post;

    // UI state & form fields (must be public for Livewire binding)
    public $commentBody = '';
    public $commentParentId = null;

    public $showReportForm = false;
    public $reportReason = '';
    public $reportDetails = '';

    // reaction state (computed on mount)
    public $userReaction = null;
    public $reactionsCount = [];

    protected $listeners = [
        'refreshPost' => '$refresh',
        // if parent listens for an event you dispatch:
        // 'postUpdated' => '$refresh',
    ];

    public function mount(Post $post)
    {
        $this->post = $post->load(['user']);
        $this->loadUserReaction();
        $this->computeReactionsCount();
    }

    protected function loadUserReaction()
    {
        if (! auth()->check()) {
            $this->userReaction = null;
            return;
        }

        $r = Reaction::where('user_id', auth()->id())
            ->where('reactable_type', get_class($this->post))
            ->where('reactable_id', $this->post->id)
            ->first();

        $this->userReaction = $r ? $r->reaction_type : null;
    }

    protected function computeReactionsCount()
    {
        $this->reactionsCount = Reaction::where('reactable_type', get_class($this->post))
            ->where('reactable_id', $this->post->id)
            ->selectRaw('reaction_type, count(*) as cnt')
            ->groupBy('reaction_type')
            ->pluck('cnt','reaction_type')
            ->toArray();
    }

    public function toggleReaction(string $type)
    {
        if (! auth()->check()) {
            $this->dispatchBrowserEvent('show-login-modal');
            return;
        }

        DB::transaction(function() use ($type) {
            $existing = Reaction::where('user_id', auth()->id())
                ->where('reactable_type', get_class($this->post))
                ->where('reactable_id', $this->post->id)
                ->first();

            if ($existing) {
                if ($existing->reaction_type === $type) {
                    $existing->delete();
                    $this->userReaction = null;
                    $this->reactionsCount[$type] = max(0, ($this->reactionsCount[$type] ?? 1) - 1);
                } else {
                    $old = $existing->reaction_type;
                    $existing->update(['reaction_type' => $type]);
                    $this->userReaction = $type;
                    $this->reactionsCount[$old] = max(0, ($this->reactionsCount[$old] ?? 1) - 1);
                    $this->reactionsCount[$type] = ($this->reactionsCount[$type] ?? 0) + 1;
                }
            } else {
                Reaction::create([
                    'user_id' => auth()->id(),
                    'reactable_type' => get_class($this->post),
                    'reactable_id' => $this->post->id,
                    'reaction_type' => $type,
                ]);
                $this->userReaction = $type;
                $this->reactionsCount[$type] = ($this->reactionsCount[$type] ?? 0) + 1;
            }
        });

        // notify parent (Livewire v3): bubble up
        $this->dispatch('postUpdated', $this->post->id);
    }

    public function addComment()
    {
        if (! auth()->check()) {
            $this->dispatchBrowserEvent('show-login-modal');
            return;
        }

        $this->validate([
            'commentBody' => 'required|string|max:2000',
            'commentParentId' => 'nullable|integer|exists:comments,id',
        ]);

        Comment::create([
            'post_id' => $this->post->id,
            'user_id' => auth()->id(),
            'parent_id' => $this->commentParentId,
            'body' => $this->commentBody,
        ]);

        $this->commentBody = '';
        $this->commentParentId = null;

        // refresh comment count and optionally comments preview
        $this->post->loadCount('comments');

        // bubble up to parent feed
        $this->dispatch('postUpdated', $this->post->id);
    }

    public function fileReport()
    {
        if (! auth()->check()) {
            $this->dispatchBrowserEvent('show-login-modal');
            return;
        }

        $this->validate([
            'reportReason' => 'required|string|max:100',
            'reportDetails' => 'nullable|string',
        ]);

        Report::create([
            'reporter_id' => auth()->id(),
            'reportable_type' => get_class($this->post),
            'reportable_id' => $this->post->id,
            'reason' => $this->reportReason,
            'details' => $this->reportDetails,
            'status' => 'pending',
        ]);

        $this->reportReason = $this->reportDetails = '';
        $this->showReportForm = false;

        session()->flash('message', 'Report submitted — thank you.');
    }

    public function render()
    {
        $commentsPreview = $this->post->comments()->with('user')->latest()->take(3)->get();

        return view('livewire.post-card', [
            'commentsPreview' => $commentsPreview,
        ]);
    }
}
