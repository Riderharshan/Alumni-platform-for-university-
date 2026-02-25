<?php

namespace App\Livewire;

use Livewire\Attributes\On; // Livewire v3
use Livewire\Component;
use App\Models\Story;

class StoryCarousel extends Component
{
    public ?int $storyId = null;

    /** @var array<int,array{type:string,url:string,mime?:string,thumb?:string}> */
    public array $slides = [];

    #[On('openStory')] // v3: listens to browser event
    public function loadStory($payload): void
    {
        $id = $payload['storyId'] ?? null;

        if ($id === 'create') {
            $this->storyId = null;
            $this->slides = [
                ['type' => 'hint', 'url' => '', 'mime' => 'text/hint'],
            ];
            $this->dispatch('initStoryOwl'); // JS will build owl
            return;
        }

        $this->storyId = (int) $id;
        $this->slides  = $this->fetchSlides($this->storyId);
        $this->dispatch('initStoryOwl'); // tell JS to (re)build the inner carousel
    }

    public function render()
    {
        return view('livewire.story-carousel');
    }

    protected function fetchSlides(int $storyId): array
    {
        $story = Story::with(['media' => function ($q) { $q->orderBy('position'); }])->find($storyId);

        if (!$story) return [];

        return $story->media->map(function ($m) {
            $mime = $m->mime_type ?? null;
            $isVideo = $mime ? str_starts_with($mime, 'video')
                             : (is_string($m->url) && preg_match('/\.(mp4|webm|ogg)$/i', $m->url));
            return [
                'type'  => $isVideo ? 'video' : 'image',
                'url'   => $m->url ?? $m->thumbnail_url,
                'thumb' => $m->thumbnail_url ?? $m->url,
                'mime'  => $mime ?: ($isVideo ? 'video/mp4' : 'image/jpeg'),
            ];
        })->values()->all();
    }
}
